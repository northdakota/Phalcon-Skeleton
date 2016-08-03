<?php
namespace Multiple\Frontend\Form;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Forms\Element\Radio;


class UserForm extends Form
{
    public function initialize($user = null, $options){
        $email = new Email("email", array(
            'class' => 'input-field-style',
            'id' => 'prof-email',
        ));
        $firstname = new Text("first_name", array(
            'class' => 'input-field-style',
            'id' => 'prof-firstName',
        ));

        $radio = new Radio("language");

        $lastname = new Text("last_name", array(
            'class' => 'input-field-style',
            'id' => 'prof-lastName',
        ));
        $password = new Password("newpassword", array(
            'class' => 'input-field-style',
            'id' => 'prof-pass',
            'placeholder' => 'Password',
            'value' =>''
        ));
        $password->clear();
        $passwordconfim = new Password("passwordconfim", array(
            'class' => 'input-field-style',
            'id' => 'prof-confPass',
            'placeholder' => 'Password Confim',
            'value' =>''
        ));
        $passwordconfim->clear();
        $email->setFilters(array('striptags', 'string'));
        $lastname->setFilters(array('striptags', 'string'));
        $firstname->setFilters(array('striptags', 'string'));
        $email->addValidators(
            array(
                new PresenceOf(
                    array(
                        'message' => 'The e-mail is reqired'
                    )
                ),
                new EmailValidator(
                    array(
                        'message' => 'The e-mail is not valid'
                    )
                )
            )
        );
        $this->add($email);
        $this->add($firstname);
        $this->add($lastname);
        $this->add($password);
        $this->add($passwordconfim);
        $this->add($radio);
    }
}