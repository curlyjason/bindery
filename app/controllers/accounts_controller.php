<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 *
 * @copyright     Copyright 2010, Dreaming Mind (http://dreamingmind.org)
 * @link          http://dreamingmind.com
 * @package       bindery
 * @subpackage    bindery.controller
 */
/**
 * AccountsController
 * 
 * Categories for Users that establish permission levels
 * @package       bindery
 * @subpackage    bindery.controller
*/
class AccountsController extends AppController {

	var $name = 'Accounts';
        var $uses = array('User', 'Optin');
	var $helpers = array('Form');
        var $components = array('UserRecordForm');

        /**
         * The page to route to after a logged in user verifies their password
         *
         */
        var $destination = array();

	function beforeFilter() {
//            $this->Acl->allow("user/{$this->username}::{$this->userid}",
//                    "UserRecord/{$this->username}::{$this->userid}");
            parent::beforeFilter();

	}

        /**
         * ********** TO DO ************
         * Consider a linked table to track login dates
         * Also, a linked file to record deactivation message/reason
         *
         * possibly some system to track op-in choices for email notifications and such
         * id, user_id, annoucemnt_id creation_date
         * then an announcements table with id, name
         * ******************************
         */

        function index() {
            // make the labels and field names easy to walk through
            $this->set('fields' , array(
                array('User Name','username'),
                array('Email','email'),
                array('First Name','first_name'),
                array('Last Name','last_name'),
                array('Address','address'),
                array('Address 2','address2'),
                array('City','city'),
                array('State','state'),
                array('Zip Code','zip'),
                array('Country','country'),
                array('Phone','phone'),
                array('Fax','fax'),
                array('Password', 'password'),
                array('Lists', 'group_id')
            ));
            // read the user's record
//            $this->data = $this->User->read(null, $this->Auth->user('id'));
            $this->User->contain('OptinUser.Optin.label');
            $this->data = $this->User->find('first', array('conditions'=>'User.id='.$this->Auth->user('id'), 'recursive'=>2));
            $this->data['User']['password'] = 'Encrypted for security';
	}

        /**
         * Allow user to edit their account information
         *
         * This is a modification of the simpler users/register action
         * In this case we don't force user to deal with double entry
         * email and password if they don't want to change those values
         *
         */
        function edit() {
            // couldn't get fieldlists for validationa and save to work
            // so I shift data out of the posted array then back in
            // at the end, after validation and save
            $holdFields = array();

            if (empty($this->data)) {
            if ($this->referer() != '/accounts/validate_user/return:edit') {
                $this->redirect(array('action'=>'validate_user', 'return'=>'edit'));
            }
                // didn't have post data. Use index() to read the record and proceed
                
                // these allow javascript to compress the input blocks.
                // then user can ignore them if they want
                $this->set('PUse', 'false');
                $this->set('EUse', 'false');
                $this->index(); // get data to populate the form
                $this->data['User']['repeat_email'] = $this->data['User']['email'];
                $this->data['User']['password'] = '';
                // and off we go to show the user the editing form for the first time
                
            } elseif ($this->data) {
            $this->set('PUse', $this->data['User']['pUse']);
            $this->set('EUse', $this->data['User']['eUse']);
                // have posted data

                // Do the password logic for the posted data
                if ($this->data['User']['pUse'] == 'false') {
                    // // password block was hidden. no change desired
                    //unset($this->data['User']['password']);
                        $holdFields['password'] = $this->data['User']['password'];
                        $holdFields['repeat_password'] = $this->data['User']['repeat_password'];
                        $holdFields['pMatch'] = $this->data['User']['pMatch'];
                    unset(
                        $this->data['User']['password'],
                        $this->data['User']['repeat_password'],
                        $this->data['User']['pMatch']); //don't need to validate. Not being saved

                } else {
                    // password block was exposed. User wants changes
                    $this->UserRecordForm->isPasswordMatched($this->data);
                    if ($this->data['User']['pMatch'] == 'false') {
                        // since the user has messed with the password (unsuccessfully) this will suppress the
                        $this->set('PUse', 'true'); // javascript collapsing of 'password' and force user input
                    }
                } // End of password logic for the posed data
                
                // Do the email logic for the posted data
                if ($this->data['User']['eUse'] == 'false') {
                    // email block was hidden. no change desired
                        $holdFields['email'] = $this->data['User']['email'];
                        $holdFields['repeat_email'] = $this->data['User']['repeat_email'];
                        $holdFields['eMatch'] = $this->data['User']['eMatch'];
                    unset(
                        $this->data['User']['email'],
                        $this->data['User']['repeat_email'],
                        $this->data['User']['eMatch']); //don't need to validate. Not being saved

                } else {
                    $this->UserRecordForm->isEmailMatched($this->data);
                    if ($this->data['User']['eMatch'] == 'false') {
                        // since the user has messed with the email (unsuccessfully) this will suppress the
                        $this->set('EUse', 'true'); // javascript collapsing of 'email' and force user input
                    }
                } // End the email logic for the posted data

                // Straight up, prevent id spoofing
                $this->data['User']['id'] = $this->Auth->user('id');
                $this->User->set($this->data);

                if ($this->User->validates()) {
                    if ($this->User->save()) {
                        $this->restoreFilteredFields($holdFields);
                        $this->Session->setFlash(__('Your User information has been saved', true));
                        $this->redirect(array('action'=>'index'));
                    } else {
                        if ($this->User->valid == false) {
                            // found suspicious entries/modifications in key data
                            // force re-login to verify user
                            $prompt = 'There was some suspicious data in that form. For your protection, please re-enter your password';
                            $redirect = array('controller' => 'account', 'action' => 'edit');
                            $this->Session->setFlash($prompt, 'default', $redirect, 'hackcheck');
                            $this->redirect(array('controller' => 'accounts', 'action' => 'validate_user', 'id' => $this->userid));
                        }
                        echo 'save failed\n';
                        $this->restoreFilteredFields($holdFields);
                        $this->Session->setFlash('An error occured while writing your User. Please try again');
                        //unset($this->data);
                        if ($this->data['User']['pUse'] == 'true') {
                            $this->data['User']['password'] = $this->data['User']['repeat_password'];
                        }

                    }
                } else { // didn't validate
                    $this->restoreFilteredFields($holdFields);
//                    debug($holdFields);
//                    debug($this->User->validationErrors);
//                    debug($this->data);
                    $this->Session->setFlash('Please correct the indicated problems.');
                    if ($this->data['User']['pUse']=='true') {
//                        debug($this->data);
                        //$this->data['User']['pUse'] = 'true';
                        $this->UserRecordForm->resetPasswordFields($this->data,$this->User->validationErrors);
                        $this->UserRecordForm->resetPasswordError($this->User->validationErrors);
                    } else {
                         $this->data['User']['password'] = $this->data['User']['repeat_password'] = '';
                    }
                    if ($this->data['User']['eUse']=='true') {
//                        debug($this->data);
                       //$this->data['User']['eUse'] = 'true';
                        $this->UserRecordForm->resetEmailError($this->User->validationErrors);
                    }
                }
            }
        }

        /**
         * Restore all supressed fields to the data array
         *
         * The edit action transfers some data array elements out
         * to prevent their validation or saving. This routine restores
         * them show the form can be redisplayed for further editing
         *
         * @param array $holdFields The array elements that are being supressed
         */
        function restoreFilteredFields($holdFields) {
//            debug($this->User->validationErrors);
//            debug($holdFields);
            foreach ($holdFields as $key => $val) {
                $this->data['User'][$key] = $val;
            }
        }
        
        function setDestination($dest) {
            $this->destination = $dest;
        }

        /**
         * Let user change their notification and publishing options
         *
         * ******** TO DO **************
         * search for thier current settings to populate the form
         * and route the user throug validate_user
         */
        function opt_in(){
            if ($this->referer() != '/accounts/validate_user/return:opt_in' && !isset($this->data)) {
                $this->redirect(array('action'=>'validate_user', 'return'=>'opt_in'));
            }
            $this->set('optins', $this->Optin->find('all',array('conditions'=> array('live'=>'1'))));
            $this->set('selections', $this->User->OptinUser->find('all', array('conditions'=> array('OptinUser.user_id'=>$this->userid))));
            if (isset($this->data)) {
                // got posted data
                $records = array();
                $this->User->OptinUser->deleteAll("OptinUser.user_id=".$this->userid);
                foreach($this->data['OptinUser']['optin_id'] as $key => $val){
                    array_push($records, array('user_id'=>$this->userid,'optin_id'=>$val));
                }
                $this->set('records',$records);
                $this->User->OptinUser->saveAll($records);
                $this->redirect(array('action'=>'index'));
            }
       }

        /**
         * Get User password before letting them in to change account details
         *
         * When a logged in user wants edit their account settings, make them verify password
         * Also, if suspicious edits are dectected, we'll force that validation
         *
         */
        function validate_user() {
            if (!empty($this->data)) {
            $this->set('return', $this->data['Account']['return']);
                if ($this->Auth->login(array('User'=>array('username'=>$this->username, 'password'=>$this->Auth->password($this->data['Account']['password']))))) {
                    $this->redirect(array('controller'=>'accounts','action'=>$this->data['Account']['return']));
                }
                $prompt = 'Your password was not correct';
                $this->Session->setFlash($prompt);
                $redirect = array('action'=>'validate_user', 'return'=>$this->data['Account']['return']);

            } else {
                $this->set('return', $this->params['named']['return']);
            }
        }

        /**
         * ********* TO DO *************
         * I think this is superceded by the user model hackCheck
         *
         * @return Success True or redirects to logout
         */
//        function hackCheck() {
//            if (isset($this->data['Account']['id']) && $this->data['Account']['id'] != $this->userid) {
//                unset($this->data['Account']);
//                $this->data['Account'] = array('id' => $this->userid, 'active' => FALSE);
//                $this->Account->save($this->data);
//                $this->Session->setFlash('Your account has been deactivated because of suspicious activity. Call for more details, (510) 537-9711.');
//                $this->redirect(array('controller'=>'users', 'action'=>'logout'));
//            }
//            return true;
//
//        }

}
?>
