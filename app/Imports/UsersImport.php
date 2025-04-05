<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    use Importable;
    protected $dep_id;

    /**
     * Constructor to accept department ID.
     *
     * @param int $dep_id
     */
    public function __construct(int $dep_id)
    {
        $this->dep_id = $dep_id;
    }

    /**
     * @param array $row
     * 
     * @return illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
        $password = random_int(100000, 999999);
        return new User([
            'name' => $row['name'],
            'password' => $password,
            'dep_id' => $this->dep_id,
        ]);
    }
}
