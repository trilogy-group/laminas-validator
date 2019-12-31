<?php

/**
 * @see       https://github.com/laminas/laminas-validator for the canonical source repository
 * @copyright https://github.com/laminas/laminas-validator/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-validator/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Validator;

use Laminas\Validator\Regex;

/**
 * @group      Laminas_Validator
 */
class RegexTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Ensures that the validator follows expected behavior
     *
     * @return void
     */
    public function testBasic()
    {
        /**
         * The elements of each array are, in order:
         *      - pattern
         *      - expected validation result
         *      - array of test input values
         */
        $valuesExpected = [
            ['/[a-z]/', true, ['abc123', 'foo', 'a', 'z']],
            ['/[a-z]/', false, ['123', 'A']]
            ];
        foreach ($valuesExpected as $element) {
            $validator = new Regex($element[0]);
            foreach ($element[2] as $input) {
                $this->assertEquals($element[1], $validator->isValid($input));
            }
        }
    }

    /**
     * Ensures that getMessages() returns expected default value
     *
     * @return void
     */
    public function testGetMessages()
    {
        $validator = new Regex('/./');
        $this->assertEquals([], $validator->getMessages());
    }

    /**
     * Ensures that getPattern() returns expected value
     *
     * @return void
     */
    public function testGetPattern()
    {
        $validator = new Regex('/./');
        $this->assertEquals('/./', $validator->getPattern());
    }

    /**
     * Ensures that a bad pattern results in a thrown exception upon isValid() call
     *
     * @return void
     */
    public function testBadPattern()
    {
        $this->setExpectedException('Laminas\Validator\Exception\InvalidArgumentException', 'Internal error parsing');
        $validator = new Regex('/');
    }

    /**
     * @Laminas-4352
     */
    public function testNonStringValidation()
    {
        $validator = new Regex('/./');
        $this->assertFalse($validator->isValid([1 => 1]));
    }

    /**
     * @Laminas-11863
     * @dataProvider specialCharValidationProvider
     */
    public function testSpecialCharValidation($expected, $input)
    {
        $validator = new Regex('/^[[:alpha:]\']+$/iu');
        $this->assertEquals($expected, $validator->isValid($input),
                            'Reason: ' . implode('', $validator->getMessages()));
    }

    /**
     * The elements of each array are, in order:
     *      - expected validation result
     *      - test input value
     */
    public function specialCharValidationProvider()
    {
        return [
            [true, 'test'],
            [true, 'òèùtestòò'],
            [true, 'testà'],
            [true, 'teààst'],
            [true, 'ààòòìùéé'],
            [true, 'èùòìiieeà'],
            [false, 'test99'],
        ];
    }

    public function testEqualsMessageTemplates()
    {
        $validator = new Regex('//');
        $this->assertAttributeEquals($validator->getOption('messageTemplates'),
                                     'messageTemplates', $validator);
    }

    public function testEqualsMessageVariables()
    {
        $validator = new Regex('//');
        $this->assertAttributeEquals($validator->getOption('messageVariables'),
                                     'messageVariables', $validator);
    }
}
