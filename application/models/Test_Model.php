<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Testing
 * @author Alvaro Pavez
 */
class Test_Model extends CI_Model
{

    function __construct()
    {
        //parent::__construct();
        $this->db = $this->load->database('maxauto', TRUE);
    }

    /**
     * CRUD - Select
     */

    function insertPdf($data)
    {
        $this->db->insert('test_pdf', $data);
        return $this->db->insert_id();
    }

    function getAllPdf()
    {
        $this->db->select('*');
        $this->db->from('test_pdf');
        $query = $this->db->get();
        return $query->result();
    }

    function findPdfText($data)
    {
        $this->db->select('*');
        $this->db->from('test_pdf');
        $this->db->like('text', $data);
        $query = $this->db->get();
        return $query->result();
    }
}
