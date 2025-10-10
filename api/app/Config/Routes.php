<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('home', function (RouteCollection $routes) {
    $routes->get('summary', 'Home::summary');
});

$routes->group('user', function (RouteCollection $routes) {
    $routes->post('create', 'UserManager::createUser');
    $routes->post('update', 'UserManager::updateUser');
    $routes->post('update/(:any)', 'UserManager::updateUser/$1');
    $routes->post('delete', 'UserManager::deleteUser');
});

$routes->group('auth', function (RouteCollection $routes) {
    $routes->post('login', 'Auth::validateLogin');
    $routes->get('logout', 'Auth::logout');
    $routes->post('updatePassword', 'Auth::updatePassword');
    $routes->post('deleteDefaultCookie', 'Auth::deleteDefaultCookie');
    $routes->get('validate-page', 'Auth::validatePageRequest');
});

$routes->group('pegawai', function (RouteCollection $routes) {
    $routes->post('get-data',        'Pegawai::getData');
    $routes->post('save',            'Pegawai::save');
    $routes->delete('delete',        'Pegawai::delete');
    $routes->get('detail/(:num)',    'Pegawai::detail/$1');
    $routes->post('import-data',     'Pegawai::importData');
});

$routes->group('siswa', function (RouteCollection $routes) {
    $routes->post('get-data', 'Siswa::getData');
    $routes->post('save', 'Siswa::save');
    $routes->delete('delete', 'Siswa::delete');
    $routes->get('detail/(:num)', 'Siswa::detail/$1');
    $routes->post('import-data', 'Siswa::importData');
});

$routes->group('surat-masuk', function (RouteCollection $routes) {
    $routes->post('get-data', 'SuratMasuk::getData');
    $routes->post('get-data/(:any)/(:any)', 'SuratMasuk::getData/$1/$2');
    $routes->post('save', 'SuratMasuk::save');
    $routes->delete('delete', 'SuratMasuk::deleteSurat');
    $routes->get('detail/(:num)', 'SuratMasuk::detail/$1');
    $routes->post('upload', 'SuratMasuk::uploadSuratPdf');
    $routes->delete('delete-saved-berkas', 'SuratMasuk::deleteSavedBerkas');
    $routes->post('delete-berkas', 'SuratMasuk::deleteBerkas');
});

$routes->group('surat-keluar', function (RouteCollection $routes) {
    $routes->post('get-data', 'SuratKeluar::getData');
    $routes->post('get-data/(:any)/(:any)', 'SuratKeluar::getData/$1/$2');
    $routes->post('save', 'SuratKeluar::save');
    $routes->delete('delete', 'SuratKeluar::deleteSurat');
    $routes->get('detail/(:num)', 'SuratKeluar::detail/$1');
    $routes->post('upload', 'SuratKeluar::uploadSuratPdf');
    $routes->delete('delete-saved-berkas', 'SuratKeluar::deleteSavedBerkas');
    $routes->post('delete-berkas', 'SuratKeluar::deleteBerkas');
});

$routes->group('institusi', function (RouteCollection $routes) {
    $routes->get('detail', 'DataInstitusi::getDetail');
    $routes->post('save', 'DataInstitusi::save');
    $routes->post('upload-kop', 'DataInstitusi::uploadKop');
    $routes->post('delete-kop', 'DataInstitusi::deleteKop');
});

$routes->group('pindah-sekolah', function (RouteCollection $routes) {
    $routes->post('get-data', 'PindahSekolah::getData');
    $routes->post('get-data/(:any)', 'PindahSekolah::getData/$1');
    $routes->post('save', 'PindahSekolah::save');
    $routes->post('delete', 'PindahSekolah::delete');
    $routes->get('detail/(:any)', 'PindahSekolah::getDetail/$1');
    $routes->post('find-student', 'PindahSekolah::findStudent');

    // route for letters
    $routes->get('cetak-surat-pindah', 'PindahSekolah::createSuratPindahSekolah');
    $routes->get('cetak-pindah-rayon', 'PindahSekolah::createSuratPindahRayon');
    $routes->get('cetak-lembar-mutasi-rapor', 'PindahSekolah::createLembarMutasiRapor');
});

$routes->group('pengantar-nisn', function (RouteCollection $routes) {
    $routes->post('get-data', 'PengantarNISN::getData');
    $routes->post('save', 'PengantarNISN::save');
    $routes->delete('delete', 'PengantarNISN::delete');
    $routes->get('detail/(:any)', 'PengantarNISN::getDetail/$1');

    // route for letters
    $routes->get('cetak-surat-pengantar-nisn', 'PengantarNISN::createSuratPengantarNISN');
});


service('auth')->routes($routes);
