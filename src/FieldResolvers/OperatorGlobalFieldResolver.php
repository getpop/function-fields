<?php

declare(strict_types=1);

namespace PoP\FunctionFields\FieldResolvers;

use PoP\ComponentModel\Schema\FieldQueryUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\FieldResolvers\AbstractGlobalFieldResolver;

class OperatorGlobalFieldResolver extends AbstractGlobalFieldResolver
{
    public static function getFieldNamesToResolve(): array
    {
        return [
            'sprintf',
            'concat',
            'divide',
            'arrayRandom',
            'arrayJoin',
            'arrayItem',
            'arraySearch',
            'arrayFill',
            'arrayValues',
            'arrayUnique',
            'arrayDiff',
            'arrayAddItem',
            'arrayAsQueryStr',
            'upperCase',
            'lowerCase',
            'titleCase',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'sprintf' => SchemaDefinition::TYPE_STRING,
            'concat' => SchemaDefinition::TYPE_STRING,
            'divide' => SchemaDefinition::TYPE_FLOAT,
            'arrayRandom' => SchemaDefinition::TYPE_MIXED,
            'arrayJoin' => SchemaDefinition::TYPE_STRING,
            'arrayItem' => SchemaDefinition::TYPE_MIXED,
            'arraySearch' => SchemaDefinition::TYPE_MIXED,
            'arrayFill' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_MIXED),
            'arrayValues' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_MIXED),
            'arrayUnique' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_MIXED),
            'arrayDiff' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_MIXED),
            'arrayAddItem' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_MIXED),
            'arrayAsQueryStr' => SchemaDefinition::TYPE_STRING,
            'upperCase' => SchemaDefinition::TYPE_STRING,
            'lowerCase' => SchemaDefinition::TYPE_STRING,
            'titleCase' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $nonNullableFieldNames = [
            'sprintf',
            'concat',
            'divide',
            'arrayRandom',
            'arrayJoin',
            'arrayItem',
            'arraySearch',
            'arrayFill',
            'arrayValues',
            'arrayUnique',
            'arrayDiff',
            'arrayAddItem',
            'arrayAsQueryStr',
            'upperCase',
            'lowerCase',
            'titleCase',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'sprintf' => $translationAPI->__('Replace placeholders inside a string with provided values', 'function-fields'),
            'concat' => $translationAPI->__('Concatenate two or more strings', 'function-fields'),
            'divide' => $translationAPI->__('Divide a number by another number', 'function-fields'),
            'arrayRandom' => $translationAPI->__('Randomly select one element from the provided ones', 'function-fields'),
            'arrayJoin' => $translationAPI->__('Join all the strings in an array, using a provided separator', 'function-fields'),
            'arrayItem' => $translationAPI->__('Access the element on the given position in the array', 'function-fields'),
            'arraySearch' => $translationAPI->__('Search in what position is an element placed in the array. If found, it returns its position (integer), otherwise it returns `false` (boolean)', 'function-fields'),
            'arrayFill' => $translationAPI->__('Fill a target array with elements from a source array, where a certain property is the same', 'function-fields'),
            'arrayValues' => $translationAPI->__('Return the values from a two-dimensional array', 'function-fields'),
            'arrayUnique' => $translationAPI->__('Filters out all duplicated elements in the array', 'function-fields'),
            'arrayDiff' => $translationAPI->__('Return an array containing all the elements from the first array which are not present on any of the other arrays', 'function-fields'),
            'arrayAddItem' => $translationAPI->__('Adds an element to the array', 'function-fields'),
            'arrayAsQueryStr' => $translationAPI->__('Represent an array as a string', 'function-fields'),
            'upperCase' => $translationAPI->__('Transform a string to upper case', 'function-fields'),
            'lowerCase' => $translationAPI->__('Transform a string to lower case', 'function-fields'),
            'titleCase' => $translationAPI->__('Transform a string to title case', 'function-fields'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        $translationAPI = TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            case 'sprintf':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'string',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The string containing the placeholders', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'values',
                            SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The values to replace the placeholders with inside the string', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );

            case 'concat':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'values',
                            SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Strings to concatenate', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );

            case 'divide':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'number',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_FLOAT,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Number to divide', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'by',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_FLOAT,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The division operandum', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );

            case 'arrayRandom':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'array',
                            SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_MIXED),
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Array of elements from which to randomly select one', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ]
                    ]
                );

            case 'arrayJoin':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'array',
                            SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Array of strings to be joined all together', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'separator',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Separator with which to join all strings in the array', 'function-fields'),
                        ],
                    ]
                );

            case 'arrayItem':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'array',
                            SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_MIXED),
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Array containing the element to retrieve', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'position',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Position where the element is placed in the array, starting from 0', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );

            case 'arraySearch':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'array',
                            SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_MIXED),
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Array containing the element to search', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'element',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Element to search in the array and retrieve its position', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );

            case 'arrayFill':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'target',
                            SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_MIXED),
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Array to be added elements coming from the source array', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'source',
                            SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_MIXED),
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Array whose elements will be added to the target array', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'index',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Property whose value must be the same on both arrays', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'properties',
                            SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Properties to copy from the source to the target array. If empty, all properties in the source array will be copied', 'function-fields'),
                        ],
                    ]
                );

            case 'arrayValues':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'array',
                            SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_MIXED),
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The array from which to retrieve the values', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );

            case 'arrayUnique':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'array',
                            SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_MIXED),
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The array to operate on', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );

            case 'arrayDiff':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'arrays',
                            SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_MIXED),
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The array containing all the arrays. It must have at least 2 elements', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );

            case 'arrayAddItem':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'array',
                            SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_MIXED),
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The array to add an item on', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'value',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_MIXED,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The value to add to the array', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'key',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_MIXED,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Key (string or integer) under which to add the value to the array. If not provided, the value is added without key', 'function-fields'),
                        ],
                    ]
                );

            case 'arrayAsQueryStr':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'array',
                            SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_MIXED),
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The array to represented as a string', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
            case 'upperCase':
            case 'lowerCase':
            case 'titleCase':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'text',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The string to be transformed', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
    }

    public function resolveSchemaValidationErrorDescription(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        if ($error = parent::resolveSchemaValidationErrorDescription($typeResolver, $fieldName, $fieldArgs)) {
            return $error;
        }

        // Important: The validations below can only be done if no fieldArg contains a field!
        // That is because this is a schema error, so we still don't have the $resultItem against which to resolve the field
        // For instance, this doesn't work: /?query=arrayItem(posts(),3)
        // In that case, the validation will be done inside ->resolveValue(), and will be treated as a $dbError, not a $schemaError
        if (!FieldQueryUtils::isAnyFieldArgumentValueAField($fieldArgs)) {
            $translationAPI = TranslationAPIFacade::getInstance();
            switch ($fieldName) {
                case 'arrayItem':
                    if (count($fieldArgs['array']) < $fieldArgs['position']) {
                        return sprintf(
                            $translationAPI->__('The array contains no element at position \'%s\'', 'function-fields'),
                            $fieldArgs['position']
                        );
                    };
                    return null;
                case 'arrayDiff':
                    if (count($fieldArgs['arrays']) < 2) {
                        return sprintf(
                            $translationAPI->__('The array must contain at least 2 elements: \'%s\'', 'function-fields'),
                            json_encode($fieldArgs['arrays'])
                        );
                    };
                case 'divide':
                    if ($fieldArgs['by'] === (float)0) {
                        return $translationAPI->__('Cannot divide by 0', 'function-fields');
                    }
                    // Check that all items are arrays
                    // This doesn't work before resolving the args! So doing arrayDiff([echo($langs),[en]]) fails
                    // $allArrays = array_reduce($fieldArgs['arrays'], function($carry, $item) {
                    //     return $carry && is_array($item);
                    // }, true);
                    // if (!$allArrays) {
                    //     return sprintf(
                    //         $translationAPI->__('The array must contain only arrays as elements: \'%s\'', 'function-fields'),
                    //         json_encode($fieldArgs['arrays'])
                    //     );
                    // }
                    return null;
            }
        }

        return null;
    }

    public function resolveValue(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        switch ($fieldName) {
            case 'sprintf':
                return sprintf($fieldArgs['string'], ...$fieldArgs['values']);
            case 'concat':
                return array_reduce(
                    $fieldArgs['values'],
                    function ($carry, $item) {
                        return $carry . $item;
                    },
                    ''
                );
            case 'divide':
                return (float)$fieldArgs['number'] / (float)$fieldArgs['by'];
            case 'arrayRandom':
                return $fieldArgs['array'][array_rand($fieldArgs['array'])];
            case 'arrayJoin':
                return implode($fieldArgs['separator'] ?? '', $fieldArgs['array']);
            case 'arrayItem':
                return $fieldArgs['array'][$fieldArgs['position']];
            case 'arraySearch':
                return array_search($fieldArgs['element'], $fieldArgs['array']);
            case 'arrayFill':
                // For each element in the source, iterate all the elements in the target
                // If the value for the index property is the same, then copy the properties
                $value = $fieldArgs['target'];
                $index = $fieldArgs['index'];
                foreach ($value as &$targetProps) {
                    foreach ($fieldArgs['source'] as $sourceProps) {
                        if (array_key_exists($index, $targetProps) && $targetProps[$index] == $sourceProps[$index]) {
                            $properties = $fieldArgs['properties'] ? $fieldArgs['properties'] : array_keys($sourceProps);
                            foreach ($properties as $property) {
                                $targetProps[$property] = $sourceProps[$property];
                            }
                        }
                    }
                }
                return $value;
            case 'arrayValues':
                return array_values($fieldArgs['array']);
            case 'arrayUnique':
                return array_unique($fieldArgs['array']);
            case 'arrayDiff':
                // Diff the first array against all the others
                $arrays = $fieldArgs['arrays'];
                $first = (array)array_shift($arrays);
                return array_diff($first, ...$arrays);
            case 'arrayAddItem':
                $array = $fieldArgs['array'];
                if ($fieldArgs['key']) {
                    $array[$fieldArgs['key']] = $fieldArgs['value'];
                } else {
                    $array[] = $fieldArgs['value'];
                }
                return $array;
            case 'arrayAsQueryStr':
                $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
                return $fieldQueryInterpreter->getArrayAsStringForQuery($fieldArgs['array']);
            case 'arrayUnique':
                return array_unique($fieldArgs['array']);
            case 'upperCase':
                return strtoupper($fieldArgs['text']);
            case 'lowerCase':
                return strtolower($fieldArgs['text']);
            case 'titleCase':
                return ucwords($fieldArgs['text']);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
