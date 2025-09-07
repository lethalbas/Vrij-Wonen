<?php

require_once "controller.php";
require_once __DIR__ . "/../repository/inquiries_repository.php";
require_once __DIR__ . "/../service/inquiries_service.php";

class inquiries_controller extends controller {

    private $service;

    function __construct() {
        $repository = new InquiriesRepository();
        $this->service = new InquiriesService($repository);
    }

    // get all inquiries
    function get_all() {
        return $this->service->getAllOrderedByHandled();
    }

    // complete inquiry
    function complete($id){
        return $this->service->markAsHandled($id);
    }

    // create inquiry
    function create($data){
        return $this->service->createInquiry($data);
    }

    // get inquiry by id (for API)
    function get_by_id($id) {
        return $this->service->getById($id);
    }

    // get unhandled inquiries
    function get_unhandled() {
        return $this->service->getUnhandled();
    }

    // get handled inquiries
    function get_handled() {
        return $this->service->getHandled();
    }

    // get inquiries with object info
    function get_inquiries_with_object_info() {
        return $this->service->getInquiriesWithObjectInfo();
    }

    // get inquiry with object info
    function get_inquiry_with_object_info($id) {
        return $this->service->getInquiryWithObjectInfo($id);
    }

    // toggle handled status
    function toggle_handled($id) {
        return $this->service->toggleHandledStatus($id);
    }

    // get inquiry counts
    function get_counts() {
        return $this->service->getInquiryCounts();
    }

    // update inquiry
    function update($id, $data) {
        return $this->service->updateInquiry($id, $data);
    }
}