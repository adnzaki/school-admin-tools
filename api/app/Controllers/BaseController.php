<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['sakola'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

/**
     * HTTP Status
     * 
     * @var string
     */
    protected $status = [
        112 => 'Service expired',
        113 => 'Subscription valid',
        200 => 'Token validated.',
        500 => 'Internal Server Error',
        503 => 'Unauthorized Access'
    ];

    /**
     * Validation common messages
     * 
     * @var array
     */
    protected $messages = [
        'required'              => 'Kolom {field} wajib diisi',
        'required_fill'         => 'Silakan isi {field} terlebih dahulu',
        'required_select'       => 'Silakan pilih  {field} terlebih dahulu',
        'is_natural'            => 'Kolom {field} hanya boleh diisi angka',
        'is_natural_no_zero'    => 'Kolom {field} hanya boleh diisi angka dan lebih dari nol',
        'min_length'            => 'Kolom {field} harus berisi setidaknya {param} karakter',
        'max_length'            => 'Kolom {field} tidak boleh lebih dari {param} karakter',
        'exact_length'          => 'Kolom {field} hanya boleh diisi dengan {param} karakter',
        'in_list'               => 'Pilhan {field} tidak tersedia',
        'valid_url'             => 'Kolom {field} harus berisi URL yang valid. (Contoh: http://example.com / https://example2.com)',
        'valid_email'           => 'Kolom {field} harus berisi alamat email yang valid',
        'is_unique'             => 'Username telah digunakan',
        'is_unique_email'       => 'Alamat email telah digunakan',
        'max_size'              => 'Ukuran file terlalu besar.',
        'alpha_dash'            => 'Kolom {field} hanya boleh diisi huruf, angka, underscore(_) dan strip (-).',
        'matches'               => 'Kolom {field} harus sama dengan kolom {param}',
        'greater_than'          => 'Kolom {field} harus lebih besar dari {param}',
        'greater_than_equal'    => 'Kolom {field} harus lebih besar atau sama dengan {param}',
        'less_than'             => 'Kolom {field} harus lebih kecil dari {param}',
        'less_than_equal'       => 'Kolom {field} harus lebih kecil atau sama dengan {param}',
    ];

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');
    }

    protected function setStatus($code)
    {
        return [
            'status'    => $code,
            'msg'       => $this->status[$code]
        ];
    }
}
