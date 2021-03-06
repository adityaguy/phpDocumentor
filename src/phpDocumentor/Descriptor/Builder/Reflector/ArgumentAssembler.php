<?php

declare(strict_types=1);

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link http://phpdoc.org
 */

namespace phpDocumentor\Descriptor\Builder\Reflector;

use phpDocumentor\Descriptor\ArgumentDescriptor;
use phpDocumentor\Descriptor\Tag\ParamDescriptor;
use phpDocumentor\Reflection\Php\Argument;

/**
 * Assembles an ArgumentDescriptor using an ArgumentReflector and ParamDescriptors.
 */
class ArgumentAssembler extends AssemblerAbstract
{
    /**
     * Creates a Descriptor from the provided data.
     *
     * @param Argument $data
     * @param iterable<ParamDescriptor> $params
     */
    public function create($data, iterable $params = []) : ArgumentDescriptor
    {
        $argumentDescriptor = new ArgumentDescriptor();
        $argumentDescriptor->setName($data->getName());
        $argumentDescriptor->setType($data->getType());

        foreach ($params as $paramDescriptor) {
            $this->overwriteTypeAndDescriptionFromParamTag($data, $paramDescriptor, $argumentDescriptor);
        }

        $argumentDescriptor->setDefault($data->getDefault());
        $argumentDescriptor->setByReference($data->isByReference());
        $argumentDescriptor->setVariadic($data->isVariadic());

        return $argumentDescriptor;
    }

    /**
     * Overwrites the type and description in the Argument Descriptor with that from the tag if the names match.
     */
    protected function overwriteTypeAndDescriptionFromParamTag(
        Argument $argument,
        ParamDescriptor $paramDescriptor,
        ArgumentDescriptor $argumentDescriptor
    ) : void {
        if ($paramDescriptor->getVariableName() !== $argument->getName()) {
            return;
        }

        $argumentDescriptor->setDescription($paramDescriptor->getDescription());
        $argumentDescriptor->setType($paramDescriptor->getType());
    }
}
