<?php
namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class DepcodeValidator extends ConstraintValidator {

    public function validate($value, Constraint $constraint) {
        
        // Testing constraint type
        if (!$constraint instanceof Depcode) {
            throw new UnexpectedTypeException($constraint, Depcode::class);
        }

        // In the case of a null or empty value, stopping the function.
        // A custom validator should ignore null or empty values.
        // So we let the other constraints (NotBlank, NotNull, etc.) take care of that.
        if ($value == null || $value == '') {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!preg_match('/^\d{2,3}$/', $value)) {
            // building a constraint violation with the given value 
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}

?>