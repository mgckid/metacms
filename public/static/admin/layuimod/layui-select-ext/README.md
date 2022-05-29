# layui-select-ext

#### 项目介绍
基于优秀的国产前端框架layui的下拉框增强模块，主要支持多选、无限级联动

#### 2.0版本说明
2.0是一个接近完善的版本。

- 实现动态设置选中值 set方法
- 实现自定义提示文字
- 实现ajax方式获取候选数据
- 实现禁用某些选项（基于候选数据，status=0）
- 实现设置下拉框宽度
- 实现选项搜索（仅支持无限级）
- 实现表单验证 lay-verify
- 实现自定义候选数据键名
- 重置（reset）恢复改为用set方法

#### selectN 1.2版本说明
1. selectN 支持表单值验证
verify: 'required'
2. 空值项提示可设置为数组，每级不同，如：['请选择省','请选择市','请选择县']
tips: '请选择',

#### 1.1版本说明
1. selectN无限级联动增加set方法，可通过js动态设置

#### 1.0版本说明
1. 修改一些bug
2. 无限级实现重置（reset）恢复默认值


### 配置参数


![配置参数](https://gitee.com/uploads/images/2018/0526/182854_3daaac38_724516.png "配置参数")

 **selectN 的 field 格式**
``` 
{
	idName: 'id',
	titleName: 'name',
	statusName:'status',
	childName: 'children'
}
```
 **selectN 候选数据 格式**
```
[{
	"id": 1,
	"name": "周边旅游",
	"children": [{
		"id": 24,
		"name": "广东",
		"status": 0,
		"children": [{
			"id": 7,
			"name": "广州"
		}, {
			"id": 23,
			"name": "潮州"
		}]
	}]
}, {
	"id": 5,
	"name": "国内旅游",
	"children": [{
		"id": 8,
		"name": "华北地区",
		"children": [{
			"id": 9,
			"name": "北京"
		}]
	}]
}, {
	"id": 6,
	"name": "出境旅游",
	"children": [{
		"id": 10,
		"name": "东南亚",
		"children": [{
			"id": 11,
			"name": "马来西亚",
			"children": [{
				"id": 20,
				"name": "沙巴",
				"children": [{
					"id": 21,
					"name": "美人鱼岛",
					"children": [{
						"id": 22,
						"name": "潜水"
					}]
				}]
			}]
		}]
	}]
}]
```

 **selectM 的 field 格式**
``` 
{
	idName: 'id',
	titleName: 'name',
	statusName:'status'
}
```


 **selectM 候选数据 格式**

``` 
[{
	"id": 12,
	"name": "研究生",
	"status": 0
}, {
	"id": 13,
	"name": "大学生"
}, {
	"id": 14,
	"name": "小学生"
}, {
	"id": 18,
	"name": "幼儿园"
}]
```


#### 实例说明
 [码云实例](http://moretop.gitee.io/layui-select-ext/ "码云实例") 

 [layui社区贴](http://fly.layui.com/jie/26751/ "Fly社区")