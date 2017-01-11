<?php

namespace NNV\OneSignal\Utils;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Validation
{
    /**
     * OptionsResolver instance
     *
     * @var Symfony\Component\OptionsResolver\OptionsResolver
     */
    private $optionsResolver;

    /**
     * Initialize class
     */
    public function __construct()
    {
        $this->optionsResolver = new OptionsResolver();
    }

    /**
     * Extends all function in OptionsResolver
     *
     * @param  string $methodName Method name
     * @param  array $args Args
     * @return __call
     */
    public function __call($methodName, $methodArgs)
    {
        $selfMethods = ["setMultiRequired", "setMultiDefined", "validate"];

        if (in_array($methodName, $selfMethods)) {
            return call_user_func_array([$this, $methodName], $methodArgs);
        }

        return call_user_func_array([$this->optionsResolver, $methodName], $methodArgs);
    }

    /**
     * Multiple set required options
     *
     * @param array $requiredOptions Required options
     * @return \Symfony\Component\OptionsResolver\OptionsResolver OptionsResolver instance
     */
    public function setMultiRequired(array $requiredOptions)
    {
        foreach ($requiredOptions as $requiredOption) {
            $this->optionsResolver->setRequired($requiredOption);
        }

        return $this;
    }

    /**
     * Multiple setDefined, setAllowedTypes and setAllowedValues
     *
     * @param array $definedOptions Defined options
     * @return Symfony\Component\OptionsResolver\OptionsResolver OptionsResolver instance
     */
    public function setMultiDefined(array $definedOptions)
    {
        foreach ($definedOptions as $definedKey => $definedOption) {
            $this->optionsResolver->setDefined($definedKey);

            if (isset($definedOption["allowedTypes"])) {
                $this->optionsResolver->setAllowedTypes($definedKey, $definedOption["allowedTypes"]);
            }
            if (isset($definedOption["allowedValues"])) {
                $this->optionsResolver->setAllowedValues($definedKey, $definedOption["allowedValues"]);
            }
        }

        return $this;
    }

    /**
     * Validate data
     *
     * @param  array  $data Data to validate
     * @return mixed  Symfony\Component\OptionsResolver\Exception\InvalidOptionsException or null
     */
    public function validate(array $data)
    {
        return $this->optionsResolver->resolve($data);
    }
}
