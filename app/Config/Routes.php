<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'AuthController::index');
$routes->get('/login/', 'AuthController::index');
$routes->get('/register/registration_form', 'AuthController::registration_form');
$routes->get('/forgot/recovery_page', 'AuthController::recovery_page');
$routes->post('/login/authenticate', 'AuthController::authenticate');
$routes->get('/user/logout', 'AuthController::logout');
$routes->post('/register/register_account', 'AuthController::register_account');
$routes->post('/recovery/forgot_password', 'AuthController::forgot_password');

/* ADMIN ROUTES */
$routes->group('', ['filter' => 'AdminFilter'], function ($routes) {
    // Homepage
    $routes->get('/administrator/dashboard', 'AdminController::dashboard');
    // Update document fees
    $routes->post('/administrator/update_document_fees', 'AdminController::update_document_fees');
    // Other Categories
    $routes->get('/OtherCategories/', 'OtherCategoriesController::index');
    $routes->get('/OtherCategories/getCategoryData', 'OtherCategoriesController::getCategoryData');
    $routes->post('/OtherCategories/saveCategory', 'OtherCategoriesController::saveCategory');
    $routes->get('/OtherCategories/getCategory/(:num)', 'OtherCategoriesController::getCategory/$1');
    // Database Management
    $routes->get('/dbms/', 'DbmsController::index');
    $routes->get('/dbms/backup','DbmsController::backup');
    $routes->post('/dbms/restore','DbmsController::restore');
    // Activity Log
    $routes->get('/activity/','ActivityController::index');
    $routes->get('/activity/getActivities', 'ActivityController::getActivities');
    $routes->post('/activity/filter', 'ActivityController::filter');
    $routes->post('/activity/download', 'ActivityController::download');
    // Brgy Code
    $routes->get('/BrgyCode/','BrgyCodeController::index');
    $routes->get('/BrgyCode/getBarangayData', 'BrgyCodeController::getBarangayData');
    $routes->post('/BrgyCode/saveBrgyCode', 'BrgyCodeController::saveBrgyCode');
    $routes->get('/BrgyCode/getBarangay/(:num)', 'BrgyCodeController::getBarangay/$1');
    // Purok/Zone Code
    $routes->get('/PurokZoneCode/','PurokZoneCodeController::index');
    $routes->get('/PurokZoneCode/getPurokZoneData', 'PurokZoneCodeController::getPurokZoneData');
    $routes->post('/PurokZoneCode/savePurokZoneCode', 'PurokZoneCodeController::savePurokZoneCode');
    $routes->get('/PurokZoneCode/getPurokZoneCode/(:num)', 'PurokZoneCodeController::getPurokZoneCode/$1');
    // Resident
    $routes->get('/resident/active_inactive','ResidentController::active_inactive');
    $routes->get('/resident/getResidentData','ResidentController::getResidentData');
    $routes->get('/resident/add', 'ResidentController::add');
    $routes->post('/resident/getHouseholdID', 'ResidentController::getHouseholdID');
    $routes->post('/resident/createList', 'ResidentController::createList');
    $routes->post('/resident/loadCategory', 'ResidentController::loadCategory');
    $routes->post('/resident/uploadDocument', 'ResidentController::uploadDocument');
    $routes->get('/resident/viewFile/(:segment)', 'ResidentController::viewFile/$1');
    $routes->post('/resident/save', 'ResidentController::save');
    $routes->get('/resident/load_household_member/(:segment)', 'ResidentController::load_household_member/$1');
    $routes->get('/resident/getFamilyHeads/(:segment)', 'ResidentController::getFamilyHeads/$1');
    $routes->post('/resident/saveMBR', 'ResidentController::saveMBR');
    $routes->get('/resident/getOtherInfo/(:segment)', 'ResidentController::getOtherInfo/$1');
    $routes->post('/resident/saveOtherInfo', 'ResidentController::saveOtherInfo');
    $routes->get('/resident/printHouseholdInfo/(:segment)', 'ResidentController::printHouseholdInfo/$1');
    $routes->get('/resident/fetchIndividual/(:segment)', 'ResidentController::fetchIndividual/$1');
    $routes->get('/resident/edit_household/(:num)', 'ResidentController::edit_household/$1');
    $routes->post('/resident/doneSubmission', 'ResidentController::doneSubmission');
    $routes->get('/resident/fetchPurokList/(:num)', 'ResidentController::fetchPurokList/$1');
    // PRINT HOUSEHOLD FORM
    $routes->get('/resident/view_form/(:segment)', 'ResidentController::view_form/$1');
    // FOR APPROVAL
    $routes->get('/resident/for_approval', 'ResidentController::for_approval');
    $routes->get('/resident/getListOfForApproval', 'ResidentController::getListOfForApproval');
    $routes->get('/resident/view_approval/(:segment)', 'ResidentController::view_approval/$1');
    $routes->post('/resident/approve', 'ResidentController::approve');
    // Barangay Profile
    $routes->get('/brgy_profile/','BrgyProfileController::index');
    $routes->get('/brgy_profile/getProfile','BrgyProfileController::getProfile');
    $routes->post('/brgy_profile/saveProfile', 'BrgyProfileController::saveProfile');
    // Brgy Official
    $routes->get('/official/','OfficialController::index');
    $routes->get('/official/getOfficialData', 'OfficialController::getOfficialData');
    $routes->post('/official/saveOfficial', 'OfficialController::saveOfficial');
    $routes->get('/official/getOfficial/(:num)', 'OfficialController::getOfficial/$1');
    // Banner
    $routes->get('/banner/','BannerController::index');
    $routes->get('/banner/getBannerData', 'BannerController::getBannerData');
    $routes->post('/banner/saveBanner', 'BannerController::saveBanner');
    $routes->get('/banner/getBanner/(:num)', 'BannerController::getBanner/$1');
    // Post
    $routes->get('/post/','PostController::index');
    $routes->get('/post/getPostData', 'PostController::getPostData');
    $routes->post('/post/savePost', 'PostController::savePost');
    $routes->get('/post/getPost/(:num)', 'PostController::getPost/$1');
    // Downloadables
    $routes->get('/administrator/download_list_household_heads', 'AdminController::download_list_household_heads');
    $routes->get('/administrator/download_list_family_heads', 'AdminController::download_list_family_heads');
    $routes->get('/administrator/download_list_senior_citizens', 'AdminController::download_list_senior_citizens');
    $routes->get('/administrator/download_list_with_disabilities', 'AdminController::download_list_with_disabilities');
    $routes->get('/administrator/download_list_with_comorbidities', 'AdminController::download_list_with_comorbidities');
    $routes->get('/administrator/download_list_with_trainings', 'AdminController::download_list_with_trainings');
    $routes->get('/administrator/download_list_with_gprograms', 'AdminController::download_list_with_gprograms');
    $routes->get('/administrator/download_residents_information', 'AdminController::download_residents_information');
    $routes->get('/administrator/download_issued_certificates', 'AdminController::download_issued_certificates');
    // User Management
    $routes->get('/user_management/', 'UserController::index');
    $routes->get('/user_management/getUserData', 'UserController::getUserData');
    $routes->post('/user_management/saveUser', 'UserController::saveUser');
    $routes->get('/user_management/getUser/(:num)', 'UserController::getUser/$1');
    $routes->get('/user_management/approve/(:num)', 'UserController::approve/$1');
    $routes->get('/user_management/decline/(:num)', 'UserController::decline/$1');
     // Certification
     $routes->get('/certification/','CertificateController::index');
     $routes->get('/certification/getCertificateData', 'CertificateController::getCertificateData');
     $routes->post('/certification/saveCertificate', 'CertificateController::saveCertificate');
     $routes->get('/certification/getCertificate/(:num)', 'CertificateController::getCertificate/$1');
     $routes->get('/certification/issue/(:num)', 'CertificateController::issue/$1');
     $routes->get('/certification/print/(:num)', 'CertificateController::print/$1');
     $routes->get('/certification/getDocumentFee/(:segment)', 'CertificateController::getDocumentFee/$1');
     $routes->post('/certification/upload_file', 'CertificateController::upload_file');
     $routes->get('/certification/viewFile/(:segment)', 'ResidentController::viewFile/$1');
     // Payment
     $routes->get('/certification/getDetails/(:num)', 'CertificateController::getDetails/$1');
     $routes->post('/certification/savePayment', 'CertificateController::savePayment');
     // My profile
     $routes->get('/profile/myAccount', 'ProfileController::profile');
     $routes->post('/profile/saveUser', 'ProfileController::saveUser');
     // Compose Message
     $routes->get('/query_builder', 'QueryController::index');
     $routes->get('/query_builder/getDefaultData', 'QueryController::getDefaultData');
     $routes->post('/query_builder/filter', 'QueryController::filter');
     $routes->post('/query_builder/export_data', 'QueryController::export_data');
     $routes->post('/query_builder/sendMessage', 'QueryController::sendMessage');
     // Maps
     $routes->get('/maps', 'MapController::index');
     $routes->get('/maps/getGeoJson', 'MapController::getGeoJson');
     $routes->get('/maps/import', 'MapController::view_import_page');
     $routes->post('/maps/import/geoJSON', 'MapController::import');
});

/** RESIDENT ROUTES */
$routes->group('', ['filter' => 'ResidentFilter'], function ($routes) {
    // Homepage
    $routes->get('/resident/dashboard', 'ResidentAccountController::dashboard');
    // My profile
    $routes->get('/profile/myAccount2', 'ProfileController::profile');
    $routes->post('/profile/saveUser2', 'ProfileController::saveUser');
    // Certification
    // $routes->get('/certification/', 'CertificateController::index');
    $routes->get('/certification/getCertificateData2', 'CertificateController::getCertificateData');
    $routes->post('/certification/saveCertificate2', 'CertificateController::saveCertificate');
    $routes->get('/certification/getCertificate2/(:num)', 'CertificateController::getCertificate/$1');
    // $routes->get('/certification/issue/(:num)', 'CertificateController::issue/$1');
    // $routes->get('/certification/print/(:num)', 'CertificateController::print/$1');
    $routes->get('/certification/getDocumentFee2/(:segment)', 'CertificateController::getDocumentFee/$1');
    $routes->get('/certification/download/(:segment)', 'CertificateController::download/$1');
    // Resident
    $routes->get('/resident/active_inactive2','ResidentController::active_inactive');
    $routes->get('/resident/getResidentData2','ResidentController::getResidentData');
    $routes->get('/resident/add2', 'ResidentController::add');
    $routes->post('/resident/getHouseholdID2', 'ResidentController::getHouseholdID');
    $routes->post('/resident/createList2', 'ResidentController::createList');
    $routes->post('/resident/loadCategory2', 'ResidentController::loadCategory');
    $routes->post('/resident/uploadDocument2', 'ResidentController::uploadDocument');
    $routes->get('/resident/viewFile2/(:segment)', 'ResidentController::viewFile/$1');
    $routes->post('/resident/save2', 'ResidentController::save');
    $routes->get('/resident/load_household_member2/(:segment)', 'ResidentController::load_household_member/$1');
    $routes->get('/resident/getFamilyHeads2/(:segment)', 'ResidentController::getFamilyHeads/$1');
    $routes->post('/resident/saveMBR2', 'ResidentController::saveMBR');
    $routes->get('/resident/getOtherInfo2/(:segment)', 'ResidentController::getOtherInfo/$1');
    $routes->post('/resident/saveOtherInfo2', 'ResidentController::saveOtherInfo');
    $routes->get('/resident/printHouseholdInfo2/(:segment)', 'ResidentController::printHouseholdInfo/$1');
    $routes->get('/resident/fetchIndividual2/(:segment)', 'ResidentController::fetchIndividual/$1');
    $routes->get('/resident/edit_household2/(:num)', 'ResidentController::edit_household/$1');
    $routes->post('/resident/doneSubmission2', 'ResidentController::doneSubmission');
    $routes->get('/resident/fetchPurokList2/(:num)', 'ResidentController::fetchPurokList/$1');
});

/** MAIN ROUTES */
$routes->group('', ['filter' => 'MainFilter'], function ($routes) {
    // Homepage
    $routes->get('/main/dashboard', 'MainController::dashboard');
    // Update document fees
    $routes->post('/main/update_document_fees', 'MainController::update_document_fees');
    // Other Categories
    $routes->get('/OtherCategories3/', 'OtherCategoriesController::index');
    $routes->get('/OtherCategories/getCategoryData3', 'OtherCategoriesController::getCategoryData');
    $routes->post('/OtherCategories/saveCategory3', 'OtherCategoriesController::saveCategory');
    $routes->get('/OtherCategories/getCategory3/(:num)', 'OtherCategoriesController::getCategory/$1');
    // Database Management
    $routes->get('/dbms3/', 'DbmsController::index');
    $routes->get('/dbms/backup3','DbmsController::backup');
    $routes->post('/dbms/restore3','DbmsController::restore');
    // Activity Log
    $routes->get('/activity3/','ActivityController::index');
    $routes->get('/activity/getActivities3', 'ActivityController::getActivities');
    $routes->post('/activity/filter3', 'ActivityController::filter');
    $routes->post('/activity/download3', 'ActivityController::download');
    // Brgy Code
    $routes->get('/BrgyCode3/','BrgyCodeController::index');
    $routes->get('/BrgyCode/getBarangayData3', 'BrgyCodeController::getBarangayData');
    $routes->post('/BrgyCode/saveBrgyCode3', 'BrgyCodeController::saveBrgyCode');
    $routes->get('/BrgyCode/getBarangay3/(:num)', 'BrgyCodeController::getBarangay/$1');
    // Purok/Zone Code
    $routes->get('/PurokZoneCode3/','PurokZoneCodeController::index');
    $routes->get('/PurokZoneCode/getPurokZoneData3', 'PurokZoneCodeController::getPurokZoneData');
    $routes->post('/PurokZoneCode/savePurokZoneCode3', 'PurokZoneCodeController::savePurokZoneCode');
    $routes->get('/PurokZoneCode/getPurokZoneCode3/(:num)', 'PurokZoneCodeController::getPurokZoneCode/$1');
    // Resident
    $routes->get('/resident/active_inactive3','ResidentController::active_inactive');
    $routes->get('/resident/getResidentData3','ResidentController::getResidentData');
    $routes->get('/resident/add3', 'ResidentController::add');
    $routes->post('/resident/getHouseholdID3', 'ResidentController::getHouseholdID');
    $routes->post('/resident/createList3', 'ResidentController::createList');
    $routes->post('/resident/loadCategory3', 'ResidentController::loadCategory');
    $routes->post('/resident/uploadDocument3', 'ResidentController::uploadDocument');
    $routes->get('/resident/viewFile3/(:segment)', 'ResidentController::viewFile/$1');
    $routes->post('/resident/save3', 'ResidentController::save');
    $routes->get('/resident/load_household_member3/(:segment)', 'ResidentController::load_household_member/$1');
    $routes->get('/resident/getFamilyHeads3/(:segment)', 'ResidentController::getFamilyHeads/$1');
    $routes->post('/resident/saveMBR3', 'ResidentController::saveMBR');
    $routes->get('/resident/getOtherInfo3/(:segment)', 'ResidentController::getOtherInfo/$1');
    $routes->post('/resident/saveOtherInfo3', 'ResidentController::saveOtherInfo');
    $routes->get('/resident/printHouseholdInfo3/(:segment)', 'ResidentController::printHouseholdInfo/$1');
    $routes->get('/resident/fetchIndividual3/(:segment)', 'ResidentController::fetchIndividual/$1');
    $routes->get('/resident/edit_household3/(:num)', 'ResidentController::edit_household/$1');
    $routes->post('/resident/doneSubmission3', 'ResidentController::doneSubmission');
    $routes->get('/resident/fetchPurokList3/(:num)', 'ResidentController::fetchPurokList/$1');
    // PRINT HOUSEHOLD FORM
    $routes->get('/resident/view_form3/(:segment)', 'ResidentController::view_form/$1');
    // FOR APPROVAL
    $routes->get('/resident/for_approval3', 'ResidentController::for_approval');
    $routes->get('/resident/getListOfForApproval3', 'ResidentController::getListOfForApproval');
    $routes->get('/resident/view_approval3/(:segment)', 'ResidentController::view_approval/$1');
    $routes->post('/resident/approve3', 'ResidentController::approve');
    // Barangay Profile
    $routes->get('/brgy_profile3/','BrgyProfileController::index');
    $routes->get('/brgy_profile/getProfile3','BrgyProfileController::getProfile');
    $routes->post('/brgy_profile/saveProfile3', 'BrgyProfileController::saveProfile');
    // Brgy Official
    $routes->get('/official3/','OfficialController::index');
    $routes->get('/official/getOfficialData3', 'OfficialController::getOfficialData');
    $routes->post('/official/saveOfficial3', 'OfficialController::saveOfficial');
    $routes->get('/official/getOfficial3/(:num)', 'OfficialController::getOfficial/$1');
    // Banner
    $routes->get('/banner3/','BannerController::index');
    $routes->get('/banner/getBannerData3', 'BannerController::getBannerData');
    $routes->post('/banner/saveBanner3', 'BannerController::saveBanner');
    $routes->get('/banner/getBanner3/(:num)', 'BannerController::getBanner/$1');
    // Post
    $routes->get('/post3/','PostController::index');
    $routes->get('/post/getPostData3', 'PostController::getPostData');
    $routes->post('/post/savePost3', 'PostController::savePost');
    $routes->get('/post/getPost3/(:num)', 'PostController::getPost/$1');
    // Downloadables
    $routes->get('/main/download_list_household_heads', 'MainController::download_list_household_heads');
    $routes->get('/main/download_list_family_heads', 'MainController::download_list_family_heads');
    $routes->get('/main/download_list_senior_citizens', 'MainController::download_list_senior_citizens');
    $routes->get('/main/download_list_with_disabilities', 'MainController::download_list_with_disabilities');
    $routes->get('/main/download_list_with_comorbidities', 'MainController::download_list_with_comorbidities');
    $routes->get('/main/download_list_with_trainings', 'MainController::download_list_with_trainings');
    $routes->get('/main/download_list_with_gprograms', 'MainController::download_list_with_gprograms');
    $routes->get('/main/download_residents_information', 'MainController::download_residents_information');
    $routes->get('/main/download_issued_certificates', 'MainController::download_issued_certificates');
    // User Management
    $routes->get('/user_management3/', 'UserController::index');
    $routes->get('/user_management/getUserData3', 'UserController::getUserData');
    $routes->post('/user_management/saveUser3', 'UserController::saveUser');
    $routes->get('/user_management/getUser3/(:num)', 'UserController::getUser/$1');
    $routes->get('/user_management/approve3/(:num)', 'UserController::approve/$1');
    $routes->get('/user_management/decline3/(:num)', 'UserController::decline/$1');
     // Certification
     $routes->get('/certification3/','CertificateController::index');
     $routes->get('/certification/getCertificateData3', 'CertificateController::getCertificateData');
     $routes->post('/certification/saveCertificate3', 'CertificateController::saveCertificate');
     $routes->get('/certification/getCertificate3/(:num)', 'CertificateController::getCertificate/$1');
     $routes->get('/certification/issue3/(:num)', 'CertificateController::issue/$1');
     $routes->get('/certification/print3/(:num)', 'CertificateController::print/$1');
     $routes->get('/certification/getDocumentFee3/(:segment)', 'CertificateController::getDocumentFee/$1');
     // Payment
     $routes->get('/certification/getDetails3/(:num)', 'CertificateController::getDetails/$1');
     $routes->post('/certification/savePayment3', 'CertificateController::savePayment');
     // My profile
     $routes->get('/profile/myAccount3', 'ProfileController::profile');
     $routes->post('/profile/saveUser3', 'ProfileController::saveUser');
     // Compose Message
    $routes->get('/query_builder3', 'QueryController::index');
    $routes->get('/query_builder/getDefaultData3', 'QueryController::getDefaultData');
    $routes->post('/query_builder/filter3', 'QueryController::filter');
    $routes->post('/query_builder/export_data3', 'QueryController::export_data');
    $routes->post('/query_builder/sendMessage3', 'QueryController::sendMessage');
    $routes->get('/query_builder/fetchPurokList3/(:num)', 'QueryController::fetchPurokList/$1');
    // Maps
    $routes->get('/maps3', 'MapController::index');
    $routes->get('/maps/getGeoJson3', 'MapController::getGeoJson');
    $routes->get('/maps/import3', 'MapController::view_import_page');
    $routes->post('/maps/import/geoJSON3', 'MapController::import');
    // SET ENCODING SCHEDULE
    $routes->post('/main/setSchedule', 'MainController::update_encoding_schedule');

});
 

// 404 PAGE NOT FOUND
$routes->set404Override('App\Controllers\Error::show404');
