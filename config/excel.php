<?php

return [

    'extension_detector' => [
        'xlsx' => \Maatwebsite\Excel\Excel::XLSX,
        'xls'  => \Maatwebsite\Excel\Excel::XLS,
        'csv'  => \Maatwebsite\Excel\Excel::CSV,
        'tsv'  => \Maatwebsite\Excel\Excel::TSV,
        'ods'  => \Maatwebsite\Excel\Excel::ODS,
        'slk'  => \Maatwebsite\Excel\Excel::SLK,
        'xml'  => \Maatwebsite\Excel\Excel::XML,
        'gnumeric' => \Maatwebsite\Excel\Excel::GNUMERIC,
        'htm'  => \Maatwebsite\Excel\Excel::HTML,
        'html' => \Maatwebsite\Excel\Excel::HTML,
    ],

    'extension' => 'xlsx',

    'csv' => [
        'delimiter'        => ',',
        'enclosure'        => '"',
        'escape_character' => '\\',
        'contiguous'       => false,
        'input_encoding'   => 'UTF-8',
    ],

    'properties' => [
        'creator'        => 'Laravel',
        'lastModifiedBy' => 'Laravel',
        'title'          => 'Laravel Excel',
        'description'    => 'Laravel Excel',
        'subject'        => 'Laravel Excel',
        'keywords'       => 'Laravel Excel',
        'category'       => 'Laravel Excel',
        'manager'        => 'Laravel',
        'company'        => 'Laravel',
    ],
];
