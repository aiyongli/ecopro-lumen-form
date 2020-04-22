<?php
namespace Ecopro\Form\Concerns;

use Doctrine\Common\Annotations\AnnotationException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

trait ValidateSymfony
{
    /**
     * @var ValidatorInterface
     */
    protected $symfonyValidator;
    /**
     * @var Constraint|Constraint[]
     */
    protected $symfonyConstraints = null;
    /**
     * @var string|GroupSequence|(string|GroupSequence)[]|null
     */
    protected $symfonyGroups = null;

    /**
     * 默认使用注解验证
     * @return ValidatorInterface
     */
    public function getSymfonyValidator()
    {
        $this->symfonyValidator =  $this->symfonyValidator ?? (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();

        return $this->symfonyValidator;
    }

    /**
     * 设置验证器
     * @param ValidatorInterface $symfonyValidator
     * @return static
     */
    public function setSymfonyValidator(ValidatorInterface $symfonyValidator)
    {
        $this->symfonyValidator =  $symfonyValidator;

        return $this;
    }

    /**
     * @param Constraint|Constraint[]|null $constraints
     * @param string|GroupSequence|(string|GroupSequence)[]|null $groups
     * @return static
     */
    public function setSymfonyConstraints($constraints = null, $groups = null)
    {
        $this->symfonyConstraints = $constraints;
        $this->symfonyGroups = $groups;

        return $this;
    }

    /**
     * 表单Symfony验证，默认使用注解验证
     */
    public function validateSymfony()
    {
        try {
            $violations = $this->getSymfonyValidator()->validate($this, $this->symfonyConstraints, $this->symfonyGroups);
            if($violations->count() > 0) {
                return $violations;
            }
        } catch(AnnotationException $e) {
            $message = $e->getMessage();
            // $errMessage = substr($message, strrpos($message,':') + 1);

            throw new \Exception(sprintf("注解异常(%s)", $message));
        }

        return [];
    }
}
