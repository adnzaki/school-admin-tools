<?php

namespace App\Filters;

use App\Models\UserInstitusiModel;
use App\Models\InstitusiModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class InstitusiAktifFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $userInstitusiModel = new UserInstitusiModel();
        $institusiId = $userInstitusiModel->getInstitusiIdByCurrentUser();

        if (! $institusiId) {
            return service('response')
                ->setStatusCode(403)
                ->setJSON([
                    'status' => 'failed',
                    'error' => lang('General.notRegistered')
                ]);
        }

        $institusiModel = new InstitusiModel();

        if (! $institusiModel->isInstitusiAktif($institusiId)) {
            return service('response')
                ->setStatusCode(403)
                ->setJSON([
                    'status' => 'failed',
                    'error' => lang('General.inactiveInstitute')
                ]);
        }

        // Lolos validasi
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
