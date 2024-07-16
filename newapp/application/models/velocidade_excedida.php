<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Velocidade_excedida extends CI_Model 
{   
    private $company;
    private $parameters;
    private $rastreamento;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('relatorios/velocidade');
    }

    public function generate($cnpj, $begin, $end, $parameters, $plates = null, $filter = null)
    {
        $this->parameters = $parameters;

        $dates = $this->dates($begin, $end);

        $plates = $this->plates($plates, $filter);

        $vehicles = $this->vehicles($cnpj, $plates);

        $reports = [];

        foreach ($vehicles as $index => $vehicle) 
        {
            if ($index == 1) 
            {
                $exist = $this->exist($vehicle, $dates);

                $tracks = $this->tracks($vehicle, $dates, $exist);

                $report = $this->report($tracks);

                $merged = array_merge($exist, $report);

                if (count($merged)) 
                {
                    $reports[] = $merged;
                }
            }
        }

        // dd($this->sort($reports));

        return $this->sort($reports);

        // $existing = $this->existing($vehicles, $range);

        // dd($existing);

        // $tracks = $this->tracks($vehicles, $range);

        // dd($tracks);

        // $normalized = $this->normalize($tracks, $this->vehicles);

        // foreach ($this->vehicles as $vehicle) 
        // {
        //     $existing = $this->existing($vehicle->serial, $range);

        //     $cleaned = $this->clean($tracks);

        //     $flatted = $this->flatter($cleaned);

        //     $periods = $this->report($vehicle, $flatted, $existing['data']);

        //     if (count($periods)) 
        //     {
        //         $sorted = array_order_by($periods, 'data', SORT_ASC, 'inicio', SORT_ASC);

        //         $grouped = array_group_by($sorted, 'placa', false);

        //         $report[] = $grouped;
        //     }
        // }

        // return $report;

        // if (count($infos)) {

        //     $t_up_vel = '';
        //     $t_anterior = "";
        //     $t_inicio = '';
        //     $t_fim = '';
        //     $i = 0;
        //     $velocidade = 0;
        //     $conta_media = 0;
        //     $velocidades_media = 0;
        //     $acima = false;

        //     foreach ($infos as $info) {

        //         if($info->VEL < 130){

        //             $t_anterior = $i == 0 ? $info->DATA : $t_anterior;
        //             $tp_anterior = strtotime($t_anterior);
        //             $tp_atual = strtotime($info->DATA);
        //             $vel_ant = $i == 0 ? $info->VEL : $this->velocidade->validar_velocidade($vel_ant, $info->VEL);
        //             $rpm_ant = $i == 0 ? $info->RPM : $rpm_ant;
        //             $lat_ant = $i == 0 ? $info->X : $lat_ant;
        //             $lgt_ant = $i == 0 ? $info->Y : $lgt_ant;
        //             //echo $vel_ant.' - '.$params->limite_velocidade;
        //             if ($vel_ant > $params->limite_velocidade) {

        //                 $conta_media++;
        //                 $velocidades_media += $vel_ant;

        //                 $t_inicio = $t_inicio == '' ? $t_anterior : $t_inicio;
        //                 $t_up_vel += ($tp_atual - $tp_anterior);
        //                 $velocidade = $velocidade == 0 ? $vel_ant : $velocidade;
        //                 if ($velocidade < $vel_ant) {
        //                     $velocidade = $vel_ant;
        //                 }
        //                 $lat = $lat_ant;
        //                 $long = $lgt_ant;
        //                 // echo $info->ID; exit();
        //                 $motorista = '';
        //                 if ($info->DRIVER_ID > 0 || $info->DRIVER_ID != '')
        //                 {
        //                     $motorista = $this->get_motorista_by_chave($info->DRIVER_ID, 'nome');
        //                 }
        //                 if ($motorista == '') 
        //                 {
        //                     $motorista = $this->get_motorista_by_id($veiculo->code_motorista, 'nome');
        //                 }

        //                 $acima = true;
        //             } else {
        //                 if ($acima) {
        //                     $t_fim = $t_anterior;
        //                     $duracao = strtotime($t_fim) - strtotime($t_inicio);
        //                     $tempo = date("H:i:s", strtotime(floor($duracao / 3600) . ':' .
        //                                     floor(($duracao % 3600) / 60) . ':' . floor((($duracao % 3600) % 60) / 1)));
                            
        //                     // if ($data_fim == $data_in) {
        //                         // $endereco = $this->geolocation->get_endereco($lat_ant, $lgt_ant);
        //                         // $endereco = lang('ver_mapa');
        //                         //$endereco = $data_fim;
        //                     // } else {
        //                     // }
        //                     $endereco = lang('ver_mapa');

        //                     $t1 = new DateTime($t_inicio);
        //                     $t2 = new DateTime($t_fim);

        //                     if($t1 == $t2){
        //                         $t_fim = $t2->add(new DateInterval('PT1S'));
        //                         $t_fim = $t_fim->format('Y-m-d H:i:s');
        //                     }

        //                     $date_graph = dh_for_humans(date('Y-n-j G:i:s', strtotime($t_inicio)), false, true);
        //                     $timeofday = explode(':', $date_graph);

        //                     foreach ($timeofday as $key => $value) {
        //                         if (!is_int($value)){
        //                             $timeofday[$key] = intval($value);
        //                         }
        //                     }

        //                     $retorno['graph'][] = array(
        //                         'placa' => $veiculo->placa, 
        //                         'vel' => $velocidade, 
        //                         'timeofday' => $timeofday, 
        //                         'dateHour' => dh_for_humans($t_inicio, true, false), 
        //                         'date' => dh_for_humans($t_inicio, false, false)
        //                     );

        //                     $retorno[] = array(
        //                         'placa' => $veiculo->placa,
        //                         'motorista' => $motorista,
        //                         'tempo' => $tempo == '00:00:00' ? '00:00:01' : $tempo,
        //                         'velocidade' => $velocidade,
        //                         'velocidade_media' => $velocidades_media / $conta_media,
        //                         'inicio' => substr($t_inicio, -8),
        //                         'fim' => substr($t_fim, -8),
        //                         'lat' => str_replace(',', '.', $lat),
        //                         'long' =>  str_replace(',', '.', $long),
        //                         'veiculo' => $veiculo->veiculo,
        //                         'endereco' => $endereco,
        //                         'data' => dh_for_humans($t_fim, false)
        //                     );

        //                     $conta_media = 0;
        //                     $velocidades_media = 0;
        //                     $t_up_vel = '';
        //                     $velocidade = 0;
        //                     $acima = false;
        //                 }

        //                 $lat = '';
        //                 $long = '';
        //                 $t_fim = '';
        //                 $t_inicio = '';
        //             }

        //             $vel_ant = $info->VEL;
        //             $t_anterior = $info->DATA;
        //             $lat_ant = $info->X;
        //             $lgt_ant = $info->Y;
        //             $i++;

        //         }
        //     }

            
            // $retorno['graph'] = array_map("unserialize", array_unique(array_map("serialize", $retorno['graph'])));
            // $retorno = array_map("unserialize", array_unique(array_map("serialize", $retorno)));
        // }

        // return $retorno;

        // $this->benchmark->mark('code_end');
    }

    public function background($vehicle, $date, $parameters)
    {
        $this->parameters = $parameters;

        $tracks = $this->tracks($vehicle, [$date]);

        $report = $this->report($tracks);

        return $report;
    }

    // public function report($data, $veiculo) 
 //    {
 //        $params = new StdClass();
 //        $params->limite_velocidade = 80;
        
 //        $retorno = array();

 //        $infos = $this->get_dados_veiculo(array(
 //            'ID' => $veiculo->serial, 
 //            'IGNITION' => 1, 
 //            'DATA >=' => $data . ' 00:00:00',
 //            'DATA <=' => $data . ' 23:59:59')
 //        );

 //        if (count($infos)) {

 //            $t_up_vel = '';
 //            $t_anterior = "";
 //            $t_inicio = '';
 //            $t_fim = '';
 //            $i = 0;
 //            $velocidade = 0;
 //            $conta_media = 0;
 //            $velocidades_media = 0;
 //            $acima = false;

 //            foreach ($infos as $info) {

 //                if($info->VEL < 130){

 //                    $t_anterior = $i == 0 ? $info->DATA : $t_anterior;
 //                    $tp_anterior = strtotime($t_anterior);
 //                    $tp_atual = strtotime($info->DATA);
 //                    $vel_ant = $i == 0 ? $info->VEL : $this->velocidade->validar_velocidade($vel_ant, $info->VEL);
 //                    $rpm_ant = $i == 0 ? $info->RPM : $rpm_ant;
 //                    $lat_ant = $i == 0 ? $info->X : $lat_ant;
 //                    $lgt_ant = $i == 0 ? $info->Y : $lgt_ant;
                    //echo $vel_ant.' - '.$params->limite_velocidade;
                    // if ($vel_ant > $params->limite_velocidade) {

                    //     $conta_media++;
                    //     $velocidades_media += $vel_ant;

                    //     $t_inicio = $t_inicio == '' ? $t_anterior : $t_inicio;
                    //     $t_up_vel += ($tp_atual - $tp_anterior);
                    //     $velocidade = $velocidade == 0 ? $vel_ant : $velocidade;
                    //     if ($velocidade < $vel_ant) {
                    //         $velocidade = $vel_ant;
                    //     }
                    //     $lat = $lat_ant;
                    //     $long = $lgt_ant;
                    //     // echo $info->ID; exit();
                    //     $motorista = '';
                    //     if ($info->DRIVER_ID > 0 || $info->DRIVER_ID != '')
                    //     {
                    //         $motorista = $this->get_motorista_by_chave($info->DRIVER_ID, 'nome');
                    //     }
                    //     if ($motorista == '') 
                    //     {
                    //         $motorista = $this->get_motorista_by_id($veiculo->code_motorista, 'nome');
                    //     }

                    //     $acima = true;
                    // } else {
                    //     if ($acima) {
                    //         $t_fim = $t_anterior;
                    //         $duracao = strtotime($t_fim) - strtotime($t_inicio);
                    //         $tempo = date("H:i:s", strtotime(floor($duracao / 3600) . ':' .
                    //                         floor(($duracao % 3600) / 60) . ':' . floor((($duracao % 3600) % 60) / 1)));
                            
                            // if ($data_fim == $data_in) {
                                // $endereco = $this->geolocation->get_endereco($lat_ant, $lgt_ant);
                                // $endereco = lang('ver_mapa');
                                //$endereco = $data_fim;
                            // } else {
                            // }
            //                 $endereco = lang('ver_mapa');

            //                 $t1 = new DateTime($t_inicio);
            //                 $t2 = new DateTime($t_fim);

            //                 if($t1 == $t2){
            //                     $t_fim = $t2->add(new DateInterval('PT1S'));
            //                     $t_fim = $t_fim->format('Y-m-d H:i:s');
            //                 }

            //                 $date_graph = dh_for_humans(date('Y-n-j G:i:s', strtotime($t_inicio)), false, true);
            //                 $timeofday = explode(':', $date_graph);

            //                 foreach ($timeofday as $key => $value) {
            //                     if (!is_int($value)){
            //                         $timeofday[$key] = intval($value);
            //                     }
            //                 }

            //                 $retorno['graph'][] = array(
            //                     'placa' => $veiculo->placa, 
            //                     'vel' => $velocidade, 
            //                     'timeofday' => $timeofday, 
            //                     'dateHour' => dh_for_humans($t_inicio, true, false), 
            //                     'date' => dh_for_humans($t_inicio, false, false)
            //                 );

            //                 $retorno[] = array(
            //                     'placa' => $veiculo->placa,
            //                     'motorista' => $motorista,
            //                     'tempo' => $tempo == '00:00:00' ? '00:00:01' : $tempo,
            //                     'velocidade' => $velocidade,
            //                     'velocidade_media' => $velocidades_media / $conta_media,
            //                     'inicio' => substr($t_inicio, -8),
            //                     'fim' => substr($t_fim, -8),
            //                     'lat' => str_replace(',', '.', $lat),
            //                     'long' =>  str_replace(',', '.', $long),
            //                     'veiculo' => $veiculo->veiculo,
            //                     'endereco' => $endereco,
            //                     'data' => dh_for_humans($t_fim, false)
            //                 );

            //                 $conta_media = 0;
            //                 $velocidades_media = 0;
            //                 $t_up_vel = '';
            //                 $velocidade = 0;
            //                 $acima = false;
            //             }

            //             $lat = '';
            //             $long = '';
            //             $t_fim = '';
            //             $t_inicio = '';
            //         }

            //         $vel_ant = $info->VEL;
            //         $t_anterior = $info->DATA;
            //         $lat_ant = $info->X;
            //         $lgt_ant = $info->Y;
            //         $i++;

            //     }
            // }

            
            // $retorno['graph'] = array_map("unserialize", array_unique(array_map("serialize", $retorno['graph'])));
            // $retorno = array_map("unserialize", array_unique(array_map("serialize", $retorno)));
        // }

        // return $retorno;

        // $this->benchmark->mark('code_end');

    // }

    private function exist($vehicle, $dates)
    {    
        $dates = array_diff($dates, [date('Y-m-d')]);

        if (count($dates))
        {
            $this->db->select('placa, serial, motorista, tempo, velocidade_media, velocidade, inicio, fim, lat, long, endereco, veiculo, data');

            $this->db->distinct();

            $this->db->where('serial', $vehicle->serial);

            $this->db->where_in('data', $dates);

            $this->db->order_by('data', 'asc');

            $query = $this->db->get('excesso_velocidade');

            if ($query->num_rows()) 
            {
                return $query->result();
            }
        }

        return [];
    }

    /**
     * Generate the row of each period
     *
     * @param array $tracks
     * @return array
     **/
    public function report($tracks)
    {
        $reports = [];

        if (count($tracks))
        {
            foreach ($tracks as $track) 
            {
                $full = $track;

                $first = array_shift($track);
                $last = array_pop($track);

                $start = new DateTime($first->data);
                $end = new DateTime($last->data);

                $speeds = array_map(function($e) {
                    return $e->velocidade;
                }, $full);

                $larger = max($speeds);

                $average = ceil(array_sum($speeds) / count($speeds));

                $report = new stdClass;
                $report->placa = $first->placa;
                $report->serial = $first->serial;
                $report->veiculo = $first->veiculo;
                $report->motorista = $first->motorista;
                $report->tempo = $start->diff($end)->format('%H:%I:%S');
                $report->data = $start->format('Y-m-d');
                $report->inicio = $start->format('H:i:s');
                $report->fim = $end->format('H:i:s');
                $report->velocidade = $larger;
                $report->velocidade_media = $average;
                $report->lat = $first->latitude;
                $report->long = $first->longitude;
                $report->endereco = '';

                $reports[] = $report;

                if ($start->format('Y-m-d') <> date('Y-m-d')) 
                {
                    $this->store($report);
                }
            }
        }

        return $reports;
    }
    
    /**
     * Get tracks by serial and date dates
     *
     * @param string $serial
     * @param array $dates
     * @return array
     **/
    public function tracks($vehicle, $dates, $except = [], $relevance = 1)
    {
        if (count($except)) 
        {
            $except = array_unique(array_map(function($e) {
                return $e->data;
            }, $except));

            $dates = array_diff($dates, $except);
        }

        if (count($dates)) 
        {
            $basetrack = $this->basetrack(
                current($dates)
            );

            $basetrack->select('ID, DATA, VEL, X, Y, DRIVER_ID');
            $basetrack->where('IGNITION', '1');
            $basetrack->where('ID', $vehicle->serial);
            $basetrack->where_in('LEFT(DATA, 10)', $dates);
            $basetrack->order_by('DATA', 'ASC');

            $track = $basetrack->get('track');

            if ($track->num_rows()) 
            {
                $tracks = $track->result();

                $periods = [];
                $before = 0;
                $period = 0;

                $limit = isset($this->parameters->limite_velocidade) ? $this->parameters->limite_velocidade : 80;

                foreach ($tracks as $track) 
                {
                    $data = new stdClass;
                    $data->placa = $vehicle->placa;
                    $data->veiculo = $vehicle->veiculo;
                    $data->serial = $track->ID;
                    $data->velocidade = $track->VEL;
                    $data->data = $track->DATA;
                    $data->latitude = $track->Y;
                    $data->longitude = $track->X;
                    $data->motorista = $track->DRIVER_ID;

                    if ($track->VEL > $limit)
                    {
                        if ($period <= ($before + $relevance))
                        {
                            $period = $before;
                        }

                        $before = $period;

                        $periods[$period][] = $data;
                    }
                    else
                    {
                        if (isset($periods[$period])) 
                        {
                            $periods[$period][] = $data;       
                        }
                        
                        $period++;
                    }
                }

                return $periods;
            }
        }

        return [];
    }

    // private function get_motorista_by_chave($chave, $select = '*')
    // {
    //     $this->db->select($select);
    //     $this->db->where('chaveiro_serial', $chave);
    //     $this->db->where('chaveiro_serial !=', 0);
    //     $this->db->where('chaveiro_serial !=', '');
    //     $this->db->limit(1);

    //     $query = $this->db->get('showtec.motoristas');

    //     if ($query->num_rows()) {
    //         return $query->row()->nome;
    //     }

    //     return false;
    // }

    // private function get_motorista_by_id($chave, $select = '*')
    // {
    //     $this->db->select($select);
    //     $this->db->where('id', $chave);
    //     $this->db->where('chaveiro_serial !=', 0);
    //     $this->db->where('chaveiro_serial !=', '');
    //     $this->db->limit(1);

    //     $query = $this->db->get('showtec.motoristas');

    //     if ($query->num_rows()) {
    //         return $query->row()->nome;
    //     }

    //     return false;
    // }

    // public function find($inicio, $fim, $placas)
    // {
    //     $this->db->where('data >=', $inicio);
    //     $this->db->where('data <=', $fim);
    //     $this->db->where_in('placa', $placas);

    //     $query = $this->db->get('excesso_velocidade');

    //     return $query->result_array();
    // }

    // public function get($where)
    // {
    //     $this->db->where($where);
    //     $query = $this->db->get('excesso_velocidade');

    //     return $query->result();
    // }
    //
    
    private function store($data)
    {
        $this->db->insert('excesso_velocidade', $data);

        return $this->db->affected_rows();
    }

    private function vehicles($cnpj, $placas)
    {
        $this->db->select('placa, serial, veiculo, code_motorista');
        $this->db->distinct();
        $this->db->where('CNPJ_', $cnpj);
        $this->db->where_in('placa', $placas);

        $query = $this->db->get('systems.cadastro_veiculo');

        return $query->result();
    }

    private function plates($plates, $filter)
    {
        if (is_array($filter))
        {
            return array_intersect($plates, $filter);
        }

        return $plates;
    }

    private function dates($begin, $end)
    {
        $begin = DateTime::createFromFormat('d/m/Y', $begin);
        $end = DateTime::createFromFormat('d/m/Y', $end);

        return date_range($begin, $end);
    }

    private function sort($reports)
    {
        $sorted = [];
        
        foreach ($reports as $report)
        {
            $temp = [];

            foreach ($report as $index => $object) 
            {
                $temp[$index] = strtotime($object->data . ' ' . $object->inicio);
            }

            array_multisort($temp, SORT_ASC, $report);

            $sorted[] = $report;
        }

        return $sorted;
    }

    private function basetrack($date)
    {
        if (strtotime($date) > strtotime('2016-01-06'))
        {
            return $this->load->database('rastreamento', true);
        }
        
        return $this->load->database('rastreamento_old', true);
    }

    // public function exists($data, $serial, $inicio = null, $fim = null)
    // {
    //     $this->db->select('id');
    //     $this->db->where('serial', $serial);
    //     if ($inicio) {
    //         $this->db->where('inicio', $inicio);
    //     }
    //     if ($fim) {
    //         $this->db->where('fim', $fim);
    //     }
    //     $this->db->where('data', $data);

    //     $query = $this->db->get('excesso_velocidade');

    //     return ($query->num_rows() > 0);
    // }
    
}