<?php

/**
 * config/hrm.php
 *
 * HRM Oracle Reports Server configuration.
 * Extracted from hrm_report_center.fmb RUN_REPORT_ACTUAL procedure.
 *
 * Set these in your .env file:
 *   HRM_REPORT_SERVER_URL=http://192.168.210.205:9002
 *   HRM_REPORT_SERVER_NAME=rep_wls_reports_fdl-server_asinst_1
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Oracle Reports Server URL
    |--------------------------------------------------------------------------
    | The base URL of the Oracle Reports Server (rwservlet).
    | From .fmb: http://192.168.210.205:9002
    */
    'report_server_url' => env('HRM_REPORT_SERVER_URL', 'http://192.168.210.205:9002'),

    /*
    |--------------------------------------------------------------------------
    | Oracle Reports Server Name
    |--------------------------------------------------------------------------
    | The named server passed as ?server= parameter to rwservlet.
    | From .fmb: rep_wls_reports_fdl-server_asinst_1
    */
    'report_server_name' => env('HRM_REPORT_SERVER_NAME', 'rep_wls_reports_fdl-server_asinst_1'),

    /*
    |--------------------------------------------------------------------------
    | Default Report Module (HRM = 1)
    |--------------------------------------------------------------------------
    | Oracle: WHERE MODULE = 1 in POPULATE_LIST_HRM query
    */
    'default_module' => env('HRM_DEFAULT_MODULE', 1),

    /*
    |--------------------------------------------------------------------------
    | Report Output Format
    |--------------------------------------------------------------------------
    | Oracle: SET_REPORT_OBJECT_PROPERTY(repid, REPORT_DESFORMAT, 'pdf')
    */
    'report_format' => env('HRM_REPORT_FORMAT', 'pdf'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout (seconds)
    |--------------------------------------------------------------------------
    | Time to wait for Oracle Reports Server to generate and return the report.
    */
    'timeout' => env('HRM_REPORT_TIMEOUT', 120),

];