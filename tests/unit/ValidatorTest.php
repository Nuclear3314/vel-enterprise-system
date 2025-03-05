<?php
namespace VEL\Tests\Unit;

use PHPUnit\Framework\TestCase;
use VEL\Includes\Validators\VEL_Validator;

class ValidatorTest extends TestCase
{
    private $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new VEL_Validator();
    }

    public function test_validate_required_field()
    {
        $data = ['name' => ''];
        $rules = ['name' => 'required'];

        $result = $this->validator->validate($data, $rules);
        $this->assertFalse($result);
    }
}