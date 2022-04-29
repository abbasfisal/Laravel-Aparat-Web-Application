<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UniqueForUserRule implements Rule
{
    /**
     * @var string
     */
    private $tbl_name;
    /**
     * @var string|null
     */
    private $colName;
    /**
     * @var string|null
     */
    private $userId;
    /**
     * @var string
     */
    private $userIdField;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $tbl_name, string $colName = null, string $userId = null, $userIdField = 'user_id')
    {

        $this->tbl_name = $tbl_name;
        $this->colName = $colName;
        $this->userId = $userId ?? Auth::id();
        $this->userIdField = $userIdField;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $field = !empty($this->colName) ? $this->colName : $attribute;

        $count = DB::table($this->tbl_name)
            ->where($field, $value)
            ->where($this->userIdField, $this->userId)
            ->count();

        return $count === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This Value Already Exist .';
    }
}
