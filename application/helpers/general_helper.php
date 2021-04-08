<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('pagination')) {
    function pagination($page, $link='', $jmlpage=0, $keyword='') {
        if ($keyword != '') {
            $linkget = '?key=' . $keyword;
        } else {
            $linkget = '';
        }
        $prev = $page - 1;
        $next = $page + 1;

        $prevhide = ($page == 1) ? 'hidden' : '' ;
        $nexthide = ($page == $jmlpage) ? 'hidden' : '' ;

        $html = '';

        // jika halaman lebih dari 10
        if ($jmlpage > 10) {
            if ($page < 5) {
                $html .= '<ul class="pagination">' .
                            '<li class="' . $prevhide . '">' .
                                '<a href="' . $link .'/' . $prev . $linkget . '" disabled aria-label="Previous">' .
                                    '<span aria-hidden="true">&laquo;</span>' .
                                '</a>' .
                            '</li>';

                $no = 1;
                for ($i=0; $i < 5; $i++) {
                    $active = ($page == $no) ? 'active' : '' ;
                    $html .= '<li class="'. $active .'"><a href="' . $link .'/' . $no . $linkget .'">' . $no . '</a></li>';
                    $no++;
                }

                $html .= '<li class="disabled"><a href="#"> ... </a></li>';
                $html .= '<li class=""><a href="' . $link .'/' . $jmlpage . $linkget .'">' . $jmlpage . '</a></li>';

                $html .= '<li class="' . $nexthide . '">' .
                            '<a href="' . $link .'/' . $next . $linkget .'" disabled aria-label="Previous">' .
                                '<span aria-hidden="true">&raquo;</span>' .
                            '</a>' .
                        '</li>' .
                        '</ul>';
            } elseif (($jmlpage - $page) < 4) {
                $html .= '<ul class="pagination">' .
                            '<li class="' . $prevhide . '">' .
                                '<a href="' . $link .'/' . $prev . $linkget . '" disabled aria-label="Previous">' .
                                    '<span aria-hidden="true">&laquo;</span>' .
                                '</a>' .
                            '</li>';
                $html .= '<li class=""><a href="' . $link .'/' . 1 . $linkget .'">' . 1 . '</a></li>';
                $html .= '<li class="disabled"><a href="#"> ... </a></li>';

                $start = $jmlpage - 4;
                $no = $start;
                for ($i=$start; $i <= $jmlpage; $i++) {
                    $active = ($page == $no) ? 'active' : '' ;
                    $html .= '<li class="'. $active .'"><a href="' . $link .'/' . $no . $linkget .'">' . $no . '</a></li>';
                    $no++;
                }

                $html .= '<li class="' . $nexthide . '">' .
                            '<a href="' . $link .'/' . $next . $linkget .'" disabled aria-label="Previous">' .
                                '<span aria-hidden="true">&raquo;</span>' .
                            '</a>' .
                        '</li>' .
                        '</ul>';
            } else {
                $html .= '<ul class="pagination">' .
                            '<li class="' . $prevhide . '">' .
                                '<a href="' . $link .'/' . $prev . $linkget . '" disabled aria-label="Previous">' .
                                    '<span aria-hidden="true">&laquo;</span>' .
                                '</a>' .
                            '</li>';

                $html .= '<li class=""><a href="' . $link .'/' . 1 . $linkget .'">' . 1 . '</a></li>';
                $html .= '<li class="disabled"><a href="#"> ... </a></li>';

                $start = $page - 1;
                $end   = $page + 1;
                $no    = $start;
                for ($i=$start; $i <= $end; $i++) {
                    $active = ($page == $no) ? 'active' : '' ;
                    $html .= '<li class="'. $active .'"><a href="' . $link .'/' . $no . $linkget .'">' . $no . '</a></li>';
                    $no++;
                }

                $html .= '<li class="disabled"><a href="#"> ... </a></li>';
                $html .= '<li class=""><a href="' . $link .'/' . $jmlpage . $linkget .'">' . $jmlpage . '</a></li>';

                $html .= '<li class="' . $nexthide . '">' .
                            '<a href="' . $link .'/' . $next . $linkget .'" disabled aria-label="Previous">' .
                                '<span aria-hidden="true">&raquo;</span>' .
                            '</a>' .
                        '</li>' .
                        '</ul>';
            }
        } else {
            // jika halaman kurang dari samadengan 10
            $html .= '<ul class="pagination">' .
                        '<li class="' . $prevhide . '">' .
                            '<a href="' . $link .'/' . $prev . $linkget . '" disabled aria-label="Previous">' .
                                '<span aria-hidden="true">&laquo;</span>' .
                            '</a>' .
                        '</li>';

            $no = 1;
            for ($i=0; $i < $jmlpage; $i++) {
                $active = ($page == $no) ? 'active' : '' ;
                $html .= '<li class="'. $active .'"><a href="' . $link .'/' . $no . $linkget .'">' . $no . '</a></li>';
                $no++;
            }

            $html .= '<li class="' . $nexthide . '">' .
                        '<a href="' . $link .'/' . $next . $linkget .'" disabled aria-label="Previous">' .
                            '<span aria-hidden="true">&raquo;</span>' .
                        '</a>' .
                    '</li>' .
                    '</ul>';
        }

        if ($jmlpage <= 1) {
            $html = '';
        }

        return $html;
    }
}

if (!function_exists('pagination2')) {
    function pagination2($page, $link='', $jmlpage=0, $intgedung='', $intmodel='', $intproses='', $keyword='') {
        if ($intgedung != '') {
            $linkget = '?intgedung=' . $intgedung . '&intmodel='.$intmodel. '&intproses='.$intproses. '&keyword='.$keyword;
        } else {
            $linkget = '';
        }
        $prev = $page - 1;
        $next = $page + 1;

        $prevhide = ($page == 1) ? 'hidden' : '' ;
        $nexthide = ($page == $jmlpage) ? 'hidden' : '' ;

        $html = '';

        // jika halaman lebih dari 10
        if ($jmlpage > 10) {
            if ($page < 5) {
                $html .= '<ul class="pagination">' .
                            '<li class="' . $prevhide . '">' .
                                '<a href="' . $link .'/' . $prev . $linkget . '" disabled aria-label="Previous">' .
                                    '<span aria-hidden="true">&laquo;</span>' .
                                '</a>' .
                            '</li>';

                $no = 1;
                for ($i=0; $i < 5; $i++) {
                    $active = ($page == $no) ? 'active' : '' ;
                    $html .= '<li class="'. $active .'"><a href="' . $link .'/' . $no . $linkget .'">' . $no . '</a></li>';
                    $no++;
                }

                $html .= '<li class="disabled"><a href="#"> ... </a></li>';
                $html .= '<li class=""><a href="' . $link .'/' . $jmlpage . $linkget .'">' . $jmlpage . '</a></li>';

                $html .= '<li class="' . $nexthide . '">' .
                            '<a href="' . $link .'/' . $next . $linkget .'" disabled aria-label="Previous">' .
                                '<span aria-hidden="true">&raquo;</span>' .
                            '</a>' .
                        '</li>' .
                        '</ul>';
            } elseif (($jmlpage - $page) < 4) {
                $html .= '<ul class="pagination">' .
                            '<li class="' . $prevhide . '">' .
                                '<a href="' . $link .'/' . $prev . $linkget . '" disabled aria-label="Previous">' .
                                    '<span aria-hidden="true">&laquo;</span>' .
                                '</a>' .
                            '</li>';
                $html .= '<li class=""><a href="' . $link .'/' . 1 . $linkget .'">' . 1 . '</a></li>';
                $html .= '<li class="disabled"><a href="#"> ... </a></li>';

                $start = $jmlpage - 4;
                $no = $start;
                for ($i=$start; $i <= $jmlpage; $i++) {
                    $active = ($page == $no) ? 'active' : '' ;
                    $html .= '<li class="'. $active .'"><a href="' . $link .'/' . $no . $linkget .'">' . $no . '</a></li>';
                    $no++;
                }

                $html .= '<li class="' . $nexthide . '">' .
                            '<a href="' . $link .'/' . $next . $linkget .'" disabled aria-label="Previous">' .
                                '<span aria-hidden="true">&raquo;</span>' .
                            '</a>' .
                        '</li>' .
                        '</ul>';
            } else {
                $html .= '<ul class="pagination">' .
                            '<li class="' . $prevhide . '">' .
                                '<a href="' . $link .'/' . $prev . $linkget . '" disabled aria-label="Previous">' .
                                    '<span aria-hidden="true">&laquo;</span>' .
                                '</a>' .
                            '</li>';

                $html .= '<li class=""><a href="' . $link .'/' . 1 . $linkget .'">' . 1 . '</a></li>';
                $html .= '<li class="disabled"><a href="#"> ... </a></li>';

                $start = $page - 1;
                $end   = $page + 1;
                $no    = $start;
                for ($i=$start; $i <= $end; $i++) {
                    $active = ($page == $no) ? 'active' : '' ;
                    $html .= '<li class="'. $active .'"><a href="' . $link .'/' . $no . $linkget .'">' . $no . '</a></li>';
                    $no++;
                }

                $html .= '<li class="disabled"><a href="#"> ... </a></li>';
                $html .= '<li class=""><a href="' . $link .'/' . $jmlpage . $linkget .'">' . $jmlpage . '</a></li>';

                $html .= '<li class="' . $nexthide . '">' .
                            '<a href="' . $link .'/' . $next . $linkget .'" disabled aria-label="Previous">' .
                                '<span aria-hidden="true">&raquo;</span>' .
                            '</a>' .
                        '</li>' .
                        '</ul>';
            }
        } else {
            // jika halaman kurang dari samadengan 10
            $html .= '<ul class="pagination">' .
                        '<li class="' . $prevhide . '">' .
                            '<a href="' . $link .'/' . $prev . $linkget . '" disabled aria-label="Previous">' .
                                '<span aria-hidden="true">&laquo;</span>' .
                            '</a>' .
                        '</li>';

            $no = 1;
            for ($i=0; $i < $jmlpage; $i++) {
                $active = ($page == $no) ? 'active' : '' ;
                $html .= '<li class="'. $active .'"><a href="' . $link .'/' . $no . $linkget .'">' . $no . '</a></li>';
                $no++;
            }

            $html .= '<li class="' . $nexthide . '">' .
                        '<a href="' . $link .'/' . $next . $linkget .'" disabled aria-label="Previous">' .
                            '<span aria-hidden="true">&raquo;</span>' .
                        '</a>' .
                    '</li>' .
                    '</ul>';
        }

        if ($jmlpage <= 1) {
            $html = '';
        }

        return $html;
    }
}

if (!function_exists('pagination3')) {
    function pagination3($page, $link='', $jmlpage=0, $from='', $to='',$intgedung='', $intcell='', $intkaryawan='') {
        if ($intgedung != '') {
            $linkget = '?from=' . $from . '&to=' . $to . '&intgedung=' . $intgedung . '&intcell='.$intcell. '&intkaryawan='.$intkaryawan;
        } else {
            $linkget = '';
        }
        $prev = $page - 1;
        $next = $page + 1;

        $prevhide = ($page == 1) ? 'hidden' : '' ;
        $nexthide = ($page == $jmlpage) ? 'hidden' : '' ;

        $html = '';

        // jika halaman lebih dari 10
        if ($jmlpage > 10) {
            if ($page < 5) {
                $html .= '<ul class="pagination">' .
                            '<li class="' . $prevhide . '">' .
                                '<a href="' . $link .'/' . $prev . $linkget . '" disabled aria-label="Previous">' .
                                    '<span aria-hidden="true">&laquo;</span>' .
                                '</a>' .
                            '</li>';

                $no = 1;
                for ($i=0; $i < 5; $i++) {
                    $active = ($page == $no) ? 'active' : '' ;
                    $html .= '<li class="'. $active .'"><a href="' . $link .'/' . $no . $linkget .'">' . $no . '</a></li>';
                    $no++;
                }

                $html .= '<li class="disabled"><a href="#"> ... </a></li>';
                $html .= '<li class=""><a href="' . $link .'/' . $jmlpage . $linkget .'">' . $jmlpage . '</a></li>';

                $html .= '<li class="' . $nexthide . '">' .
                            '<a href="' . $link .'/' . $next . $linkget .'" disabled aria-label="Previous">' .
                                '<span aria-hidden="true">&raquo;</span>' .
                            '</a>' .
                        '</li>' .
                        '</ul>';
            } elseif (($jmlpage - $page) < 4) {
                $html .= '<ul class="pagination">' .
                            '<li class="' . $prevhide . '">' .
                                '<a href="' . $link .'/' . $prev . $linkget . '" disabled aria-label="Previous">' .
                                    '<span aria-hidden="true">&laquo;</span>' .
                                '</a>' .
                            '</li>';
                $html .= '<li class=""><a href="' . $link .'/' . 1 . $linkget .'">' . 1 . '</a></li>';
                $html .= '<li class="disabled"><a href="#"> ... </a></li>';

                $start = $jmlpage - 4;
                $no = $start;
                for ($i=$start; $i <= $jmlpage; $i++) {
                    $active = ($page == $no) ? 'active' : '' ;
                    $html .= '<li class="'. $active .'"><a href="' . $link .'/' . $no . $linkget .'">' . $no . '</a></li>';
                    $no++;
                }

                $html .= '<li class="' . $nexthide . '">' .
                            '<a href="' . $link .'/' . $next . $linkget .'" disabled aria-label="Previous">' .
                                '<span aria-hidden="true">&raquo;</span>' .
                            '</a>' .
                        '</li>' .
                        '</ul>';
            } else {
                $html .= '<ul class="pagination">' .
                            '<li class="' . $prevhide . '">' .
                                '<a href="' . $link .'/' . $prev . $linkget . '" disabled aria-label="Previous">' .
                                    '<span aria-hidden="true">&laquo;</span>' .
                                '</a>' .
                            '</li>';

                $html .= '<li class=""><a href="' . $link .'/' . 1 . $linkget .'">' . 1 . '</a></li>';
                $html .= '<li class="disabled"><a href="#"> ... </a></li>';

                $start = $page - 1;
                $end   = $page + 1;
                $no    = $start;
                for ($i=$start; $i <= $end; $i++) {
                    $active = ($page == $no) ? 'active' : '' ;
                    $html .= '<li class="'. $active .'"><a href="' . $link .'/' . $no . $linkget .'">' . $no . '</a></li>';
                    $no++;
                }

                $html .= '<li class="disabled"><a href="#"> ... </a></li>';
                $html .= '<li class=""><a href="' . $link .'/' . $jmlpage . $linkget .'">' . $jmlpage . '</a></li>';

                $html .= '<li class="' . $nexthide . '">' .
                            '<a href="' . $link .'/' . $next . $linkget .'" disabled aria-label="Previous">' .
                                '<span aria-hidden="true">&raquo;</span>' .
                            '</a>' .
                        '</li>' .
                        '</ul>';
            }
        } else {
            // jika halaman kurang dari samadengan 10
            $html .= '<ul class="pagination">' .
                        '<li class="' . $prevhide . '">' .
                            '<a href="' . $link .'/' . $prev . $linkget . '" disabled aria-label="Previous">' .
                                '<span aria-hidden="true">&laquo;</span>' .
                            '</a>' .
                        '</li>';

            $no = 1;
            for ($i=0; $i < $jmlpage; $i++) {
                $active = ($page == $no) ? 'active' : '' ;
                $html .= '<li class="'. $active .'"><a href="' . $link .'/' . $no . $linkget .'">' . $no . '</a></li>';
                $no++;
            }

            $html .= '<li class="' . $nexthide . '">' .
                        '<a href="' . $link .'/' . $next . $linkget .'" disabled aria-label="Previous">' .
                            '<span aria-hidden="true">&raquo;</span>' .
                        '</a>' .
                    '</li>' .
                    '</ul>';
        }

        if ($jmlpage <= 1) {
            $html = '';
        }

        return $html;
    }
}

if (!function_exists('pagination4')) {
    function pagination4($page, $link='', $jmlpage=0, $from='', $to='',$intgedung='', $intmodel='', $intproses='') {
        if ($intgedung != '') {
            $linkget = '?from=' . $from . '&to=' . $to . '&intgedung=' . $intgedung . '&intmodel='.$intmodel. '&intproses='.$intproses;
        } else {
            $linkget = '';
        }
        $prev = $page - 1;
        $next = $page + 1;

        $prevhide = ($page == 1) ? 'hidden' : '' ;
        $nexthide = ($page == $jmlpage) ? 'hidden' : '' ;

        $html = '';

        // jika halaman lebih dari 10
        if ($jmlpage > 10) {
            if ($page < 5) {
                $html .= '<ul class="pagination">' .
                            '<li class="' . $prevhide . '">' .
                                '<a href="' . $link .'/' . $prev . $linkget . '" disabled aria-label="Previous">' .
                                    '<span aria-hidden="true">&laquo;</span>' .
                                '</a>' .
                            '</li>';

                $no = 1;
                for ($i=0; $i < 5; $i++) {
                    $active = ($page == $no) ? 'active' : '' ;
                    $html .= '<li class="'. $active .'"><a href="' . $link .'/' . $no . $linkget .'">' . $no . '</a></li>';
                    $no++;
                }

                $html .= '<li class="disabled"><a href="#"> ... </a></li>';
                $html .= '<li class=""><a href="' . $link .'/' . $jmlpage . $linkget .'">' . $jmlpage . '</a></li>';

                $html .= '<li class="' . $nexthide . '">' .
                            '<a href="' . $link .'/' . $next . $linkget .'" disabled aria-label="Previous">' .
                                '<span aria-hidden="true">&raquo;</span>' .
                            '</a>' .
                        '</li>' .
                        '</ul>';
            } elseif (($jmlpage - $page) < 4) {
                $html .= '<ul class="pagination">' .
                            '<li class="' . $prevhide . '">' .
                                '<a href="' . $link .'/' . $prev . $linkget . '" disabled aria-label="Previous">' .
                                    '<span aria-hidden="true">&laquo;</span>' .
                                '</a>' .
                            '</li>';
                $html .= '<li class=""><a href="' . $link .'/' . 1 . $linkget .'">' . 1 . '</a></li>';
                $html .= '<li class="disabled"><a href="#"> ... </a></li>';

                $start = $jmlpage - 4;
                $no = $start;
                for ($i=$start; $i <= $jmlpage; $i++) {
                    $active = ($page == $no) ? 'active' : '' ;
                    $html .= '<li class="'. $active .'"><a href="' . $link .'/' . $no . $linkget .'">' . $no . '</a></li>';
                    $no++;
                }

                $html .= '<li class="' . $nexthide . '">' .
                            '<a href="' . $link .'/' . $next . $linkget .'" disabled aria-label="Previous">' .
                                '<span aria-hidden="true">&raquo;</span>' .
                            '</a>' .
                        '</li>' .
                        '</ul>';
            } else {
                $html .= '<ul class="pagination">' .
                            '<li class="' . $prevhide . '">' .
                                '<a href="' . $link .'/' . $prev . $linkget . '" disabled aria-label="Previous">' .
                                    '<span aria-hidden="true">&laquo;</span>' .
                                '</a>' .
                            '</li>';

                $html .= '<li class=""><a href="' . $link .'/' . 1 . $linkget .'">' . 1 . '</a></li>';
                $html .= '<li class="disabled"><a href="#"> ... </a></li>';

                $start = $page - 1;
                $end   = $page + 1;
                $no    = $start;
                for ($i=$start; $i <= $end; $i++) {
                    $active = ($page == $no) ? 'active' : '' ;
                    $html .= '<li class="'. $active .'"><a href="' . $link .'/' . $no . $linkget .'">' . $no . '</a></li>';
                    $no++;
                }

                $html .= '<li class="disabled"><a href="#"> ... </a></li>';
                $html .= '<li class=""><a href="' . $link .'/' . $jmlpage . $linkget .'">' . $jmlpage . '</a></li>';

                $html .= '<li class="' . $nexthide . '">' .
                            '<a href="' . $link .'/' . $next . $linkget .'" disabled aria-label="Previous">' .
                                '<span aria-hidden="true">&raquo;</span>' .
                            '</a>' .
                        '</li>' .
                        '</ul>';
            }
        } else {
            // jika halaman kurang dari samadengan 10
            $html .= '<ul class="pagination">' .
                        '<li class="' . $prevhide . '">' .
                            '<a href="' . $link .'/' . $prev . $linkget . '" disabled aria-label="Previous">' .
                                '<span aria-hidden="true">&laquo;</span>' .
                            '</a>' .
                        '</li>';

            $no = 1;
            for ($i=0; $i < $jmlpage; $i++) {
                $active = ($page == $no) ? 'active' : '' ;
                $html .= '<li class="'. $active .'"><a href="' . $link .'/' . $no . $linkget .'">' . $no . '</a></li>';
                $no++;
            }

            $html .= '<li class="' . $nexthide . '">' .
                        '<a href="' . $link .'/' . $next . $linkget .'" disabled aria-label="Previous">' .
                            '<span aria-hidden="true">&raquo;</span>' .
                        '</a>' .
                    '</li>' .
                    '</ul>';
        }

        if ($jmlpage <= 1) {
            $html = '';
        }

        return $html;
    }
}

if (!function_exists('dateindo')) {
    function dateindo($tanggal){
        $bulan = array (1 =>   'Januari',
                    'Februari',
                    'Maret',
                    'April',
                    'Mei',
                    'Juni',
                    'Juli',
                    'Agustus',
                    'September',
                    'Oktober',
                    'November',
                    'Desember'
                );
        $split = explode('-', $tanggal);
        return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
    }
}

if (!function_exists('rupiah')) {
    function rupiah($angka){
        $hasil_rupiah = "Rp. " . number_format($angka,2,',','.');
        return $hasil_rupiah;
    }
}

if (!function_exists('angka')) {
    function angka($angka){
        $angka = number_format($angka,0,',','.');
        return $angka;
    }
}

if (!function_exists('terbilang')) {
    function penyebut($nilai) {
        $nilai = abs($nilai);
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " ". $huruf[$nilai];
        } else if ($nilai <20) {
            $temp = penyebut($nilai - 10). " Belas";
        } else if ($nilai < 100) {
            $temp = penyebut($nilai/10)." Puluh". penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " Seratus" . penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = penyebut($nilai/100) . " Ratus" . penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " Seribu" . penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = penyebut($nilai/1000) . " Ribu" . penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = penyebut($nilai/1000000) . " Juta" . penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = penyebut($nilai/1000000000) . " Milyar" . penyebut(fmod($nilai,1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = penyebut($nilai/1000000000000) . " Trilyun" . penyebut(fmod($nilai,1000000000000));
        }     
        return $temp;
    }
 
    function terbilang($nilai) {
        if($nilai<0) {
            $hasil = "minus ". trim(penyebut($nilai));
        } else {
            $hasil = trim(penyebut($nilai));
        }           
        return $hasil;
    }
}

if (!function_exists('rupiahterbilang')) {
    function rupiahterbilang($nilai){
        if($nilai<0) {
            $hasil = "minus ". trim(penyebut($nilai)) . " Rupiah";
        } else {
            $hasil = trim(penyebut($nilai)) . " Rupiah";
        }           
        return $hasil;
    }
}

if (!function_exists('getshift')) {
    function getshift($timenow){
        $shift1start  = strtotime('07:00:00');
        $shift1finish = strtotime('20:00:00');

        $shift2start1  = strtotime('20:00:01');
        $shift2start2  = strtotime('00:00:00');
        $shift2finish1 = strtotime('23:59:59');
        $shift2finish2 = strtotime('06:59:59');
        $intshift = 0;

        if ($timenow >= $shift1start && $timenow <= $shift1finish) {
          $hasil = 1;
        } else {
          $hasil = 2;
        }
        return $hasil;
    }
}