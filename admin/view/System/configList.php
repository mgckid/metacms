<?php $this->layout('Layout/admin'); ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <label for="">
            <a class="btn btn-success btn-sm"  data-power="admin/System/addConfig" href="<?=U('admin/System/addConfig')?>">添加配置</a>
        </label>
    </div>
    <div class="panel-body">
        <!--table-->
        <table class="table">
            <thead>
            <tr>
                <?php foreach ($list_init as $key=> $value):?>
                    <th><?=$value['field_name']?></th>
                <?php endforeach;?>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list_data as $value): ?>
                <tr id="row<?= $value['id'] ?>">
                    <?php foreach ($list_init as $key => $val): ?>
                        <td><?= !empty($val['enum']) ? $val['enum'][$value[$key]] : $value[$key] ?></td>
                    <?php endforeach;?>
                    <td>
                        <a class="btn btn-success btn-xs" data-power="admin/System/addConfig" href="<?= U('admin/System/editConfig',array('id'=>$value['id']))?>">编辑</a>
                        <a class="btn btn-danger btn-xs" data-power="admin/System/delConfig" href="javascript:void(0)" onclick="del(<?= $value['id'] ?>)">删除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <!--/列表-->
    </div>
</div>
