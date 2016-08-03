<?php
namespace Multiple\Backend\Form;
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\StringLength;

class AdminForm extends Form
{

    public function initialize($entity = null, $options = array())
    {
        if (!isset($options['edit'])) {
            $element = new Text("id");
            $this->add($element->setLabel("Id"));

        } else {
            $this->add(new Hidden("id"));
        }

        $email = new Text("email");
        $email->setLabel("Email");
        $email->setFilters(array('striptags', 'string'));
        $email->addValidators(
            array(
                new PresenceOf(
                    array(
                        'message' => 'Email necessarily'
                    )
                )
            )
        );
        $this->add($email);
        //
        $surname = new Text("surname");
        $surname->setLabel("Surname");
        $surname->setFilters(array('striptags', 'string'));
        $surname->addValidators(
            array(
                new PresenceOf(
                    array(
                        'message' => 'Surname necessarily'
                    )
                )
            )
        );
        $this->add($surname);
        //
        $name = new Text("name");
        $name->setLabel("Name");
        $name->setFilters(array('striptags', 'string'));
        $name->addValidators(
            array(
                new PresenceOf(
                    array(
                        'message' => 'Name necessarily'
                    )
                )
            )
        );
        $this->add($name);
        //

        if (isset($options['add'])) {

            $password = new Text('password');
            $password->setLabel("Password");
            $password->setFilters(array('striptags', 'string'));
            $password->addValidators(
                array(
                    new PresenceOf(
                        array(
                            'message' => 'Password necessarily'
                        )
                    )
                )
            );
            $this->add($password);
        }
        if (isset($options['edit'])) {
            $emptypassword= new Text('emptypassword');
            $emptypassword->setLabel("Password");
            $this->add($emptypassword);
        }



    }
}