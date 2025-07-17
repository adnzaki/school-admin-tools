<?php

namespace App\Models;

use CodeIgniter\Model;

class UserInstitusiModel extends Model
{
    protected $table            = 'tb_user_institusi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'user_id',
        'institusi_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Mendapatkan ID institusi dari pengguna yang sedang login.
     * @param int|null $userId
     * @return int|null
     */
    public function getInstitusiIdByCurrentUser(?int $userId = null): ?int
    {
        $data = $this->select('institusi_id')
            ->where('user_id', $userId ?? auth()->id())
            ->where('deleted_at', null)
            ->first();

        return $data['institusi_id'] ?? null;
    }

    /**
     * Cek apakah pengguna terdaftar dalam institusi tertentu.
     * @param int|null $userId
     * @param int $institusiId
     * @return bool
     */
    public function isCurrentUserInInstitusi(int $institusiId, ?int $userId = null): bool
    {
        return $this->where([
            'user_id'      => $userId ?? auth()->id(),
            'institusi_id' => $institusiId,
            'deleted_at'   => null
        ])->countAllResults() > 0;
    }
}
