<?php
/**
 * Created by PhpStorm.
 * User: mgckid
 * Date: 2022/1/15
 * Time: 19:08
 */

namespace app\appdal\model;


use think\Exception;


trait ModelOperation {
    protected $search_param;
    protected $multi_to_line_glue = ',';

    public function getFormInit($param) {
        return success(['record' => array_values((array)$this->form_schema())]);
    }

    public function getListInit($param) {
        return success(['record' => array_values((array)$this->list_schema())]);
    }

    public function getfilterInit($param) {
        return success(['record' => array_values((array)$this->filter_schema())]);
    }

    public function add($param) {
        try {
            $validate = validate($this->rule['add'], [], true, false);
            if ($validate->check($param) === false) {
                throw new \Exception(join(',', $validate->getError()));
            }
            $data = [];
            array_walk($param, function ($v, $k) use (&$data) {
                if (array_key_exists($k, $this->schema))
                    $data[$k] = is_scalar($v) ? trim($v) : join($this->multi_to_line_glue, $v);
            });
            $result = $this->save($data);
            return success($result);
        } catch (\Exception $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        } catch (\Error $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        }
    }

    public function edit($param) {
        try {
            $params = is_object_array($param) ? [$param] : $param;
            $message = [];
            foreach ($params as $key => $param) {
                $validate = validate($this->rule['edit'], [], true, false);
                if ($validate->check($param) === false) {
                    $message[$key + 1] = join(',', $validate->getError());
                }
            }
            if ($message) {
                if (count($message) == 1) {
                    throw new Exception(current($message));
                } else {
                    foreach ($message as $key => &$value) {
                        $value = "第{$key}行," . $value;
                    }
                    throw new \Exception(join('<br>', $message));
                }
            }

            foreach ($params as $param) {
                $data = [];
                array_walk($param, function ($v, $k) use (&$data) {
                    if (array_key_exists($k, $this->schema))
                        $data[$k] = is_scalar($v) ? trim($v) : join($this->multi_to_line_glue, $v);
                });
                $id = explode(',', $data[$this->pk]);
                unset($data[$this->pk]);
                foreach ($id as $value) {
                    $model = self::find($value);
                    $result = $model->save($data);
                }
            }
            return success(true);
        } catch (\Exception $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        } catch (\Error $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        }
    }

    function store($param) {
        try {
            $params = is_object_array($param) ? [$param] : $param;
            $message = [];
            foreach ($params as $key => $param) {
                $validate = validate($this->rule['add'], [], true, false);
                if ($validate->check($param) === false) {
                    $message[$key + 1] = join(',', $validate->getError());
                }
            }
            if ($message) {
                if (count($message) == 1) {
                    throw new Exception(current($message));
                } else {
                    foreach ($message as $key => &$value) {
                        $value = "第{$key}行," . $value;
                    }
                    throw new \Exception(join('<br>', $message));
                }
            }
            foreach ($params as $param) {
                $data = [];
                array_walk($param, function ($v, $k) use (&$data) {
                    if (array_key_exists($k, $this->schema))
                        $data[$k] = is_scalar($v) ? trim($v) : join($this->multi_to_line_glue, $v);
                });
                $model = $this->find($data[$this->pk]) ?: new self();
                $result = $model->save($data);
            }
            return success(true);
        } catch (\Exception $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        } catch (\Error $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        }
    }

    public function del($param) {
        try {
            $validate = validate($this->rule['del'], [], true, false);
            if ($validate->check($param) === false) {
                throw new \Exception(join(',', $validate->getError()));
            }
            $data = [];
            array_walk($param, function ($v, $k) use (&$data) {
                if (array_key_exists($k, $this->schema))
                    $data[$k] = is_scalar($v) ? trim($v) : $v;
            });
            $id = is_scalar($data[$this->pk]) ? explode(',', $data[$this->pk]) : $data[$this->pk];
            array_map(function ($i) {
                self::destroy($i);
            }, $id);
            return success(true);
        } catch (\Exception $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        } catch (\Error $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        }
    }

    public function import($import_param) {
        try {
            if (!$import_param)
                throw new \Exception('导入数据为空');
            $all_data = $error = [];
            $i = 1;
            foreach ($import_param as $param) {
                $i++;
                $validate = validate($this->rule['add'], [], true, false);
                if ($validate->check($param) === false) {
                    $error[] = "第{$i}行：" . join(',', $validate->getError());
                }
                $data = [];
                array_walk($param, function ($v, $k) use (&$data) {
                    if (array_key_exists($k, $this->schema))
                        $data[$k] = is_scalar($v) ? trim($v) : $v;
                });
                $all_data[] = $data;
            }
            if ($all_data) {
                $result = $this->saveAll($all_data);
            }
            if ($error)
                throw new \Exception("导入失败" . join(PHP_EOL, $error));
            return success($result);
        } catch (\Exception $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        } catch (\Error $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        }
    }

    public function getone($param) {
        try {
            $res = (array)$this->get_model_db($param)
                ->select()->toArray();
            $data = [];
            $data['record'] = $res ? current($this->formatRecord($res)) : [];
            return success($data);
        } catch (\Exception $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        } catch (\Error $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        }
    }

    protected function get_model_db($param) {
        //$this->search_param['from'] = $this->search_param['from'] ?? $this->getTable() . ' t';
        $this->search_param['field'] = $param['field'] ?? 't.*';
        $this->search_param['join'] = $param['join'] ?? [];
        $this->search_param['order'] = $param['order'] ?? 't.id desc';
        $this->search_param['group'] =$param['group'] ?? '';
        $this->search_param['page'] = $param['page'] ?? 1;
        $this->search_param['limit'] = $param['limit'] ?? 20;
        $db = self::alias('t');
        foreach ($this->search_param['join'] as $value) {
            if ($value[2] ?? '' == 'left') {
                $db->leftjoin($value[0], $value[1]);
            } elseif ($value[2] ?? '' == 'right') {
                $db->rightjoin($value[0], $value[1]);
            } else {
                $db->join($value[0], $value[1]);
            }
        }
        array_walk($this->schema, function ($v, $k) use (&$param) {
            $param[$k] = $param[$k] ?? null;
            $param[$k] = $param[$k] === '' ? null : $param[$k];
        });
        $condition = [];
        foreach ($this->getCondition($param) as $value) {
            if (end($value) === null)
                continue;
            $condition[] = $value;
        }
        if ($condition) {
            foreach ($condition as $value){
                call_user_func_array([$db,'where'],array_values($value));
            }
            //$db->where([$condition]);
        }
        return $db;
    }

    protected function formatRecord($data) {
        return $data;
    }

    protected function getCondition($param) {
        return [];
    }

    public function getlist($param) {
        try {
            $_db = $db = $this->get_model_db($param);
            $res = (array)$db->field($this->search_param['field'])
                ->page($this->search_param['page'])
                ->limit($this->search_param['limit'])
                ->order($this->search_param['order'])->select()->toArray();
            $sql = $db->getLastSql();
            $data = [];
            $data['record'] = $this->formatRecord($res);
            $data['total'] = $_db->count();;
            $data['pages'] = ceil($data['total'] / $this->search_param['limit']);
            $data['page'] = $this->search_param['page'];
            $data['limit'] = $this->search_param['limit'];
            $data['sql'] = $sql;
            return success($data);
        } catch (\Exception $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        } catch (\Error $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        }
    }

    public function getall($param) {
        try {
            $res = (array)$this->get_model_db($param)
                ->field($this->search_param['field'])
                ->order($this->search_param['order'])
                ->select()->toArray();
            $data = [];
            $data['record'] = $this->formatRecord($res);
            return success($data);
        } catch (\Exception $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        } catch (\Error $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        }
    }
}