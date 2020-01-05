<?php

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Depcode extends Constraint {
    
    public $message = 'The string "{{ string }}" must be a french depcode (between 2 and 3 digits).';
    
    public function validatedBy() {
        return \get_class($this).'Validator';    
    } 
}
?>