layui.define(['form'], function(exports) {
	var MOD_NAME = 'inte',
		o = layui.jquery,
		form = layui.form,
		i = function() {};
	i.prototype.config = function() {
		return {
			elem: '', // 元素例如（.inte,#inte）
			url: '', // 异步获取数据地址，如果设置了此参数data参数失效
			data: [], // 参数集（只要有父子级关系标识，插件会自动处理父子级显示），格式为[{},{},{},...]，上面参数url返回格式一样
			ic: 'layui-input-inline', // layui自带样式类，如果需要扩展样式可以设置类进行样式定义
			zc: 'inte-item', // 插件里面用的一个类标识，如果和你页面上标签有冲突，可以更改此参数
			primary_key: 'id', // 主键字段
			parent_key: 'pid', // 父级字段
			title_key: 'title', // 标题字段
			top_value: 0, // 最高级父值
			selected: [], // 默认选中值，例如[1,4,11]
			hint: [], // 提示，例如['请选择省','请选择市','请选择区']
			name: [], // select的name值，例如['province','city','area']
			dname: 'int', // 如果name未设置，默认select的name值为int[]
			dhint: '请选择', // 如果hint未设置，默认每个select第一个option是请选择
			is_step: false, // 是否逐步获取子级
			count: 0, // 总共显示层级，0表示不限制
			filter: new Date().getTime(),
		}
	}
	// 初始化模板
	i.prototype.template = function(e) {
		var ih = '',
			len = e.count > 0 ? e.count : (e.hint.length > 0 ? e.hint.length : (e.selected.length > 0 ? e.selected.length : 1));
		for(var i = 0; i < len; i++) {
			var data = [],
				k = i - 1;
			if(i == 0) {
				data = e.data[e.top_value];
			} else {
				if(e.selected[k] && e.data[e.selected[k]]) {
					data = e.data[e.selected[k]];
				}
			}
			ih += this.selects(e, data, k);
		}
		o(e.elem).append(ih), form.render('select', o(e.elem).attr('lay-filter')), this.click(e);
	}
	// 点击选择事件
	i.prototype.click = function(e) {
		var t = this;
		e.selected = [], o(e.elem).find('[lay-filter]').each(function() {
			var filter = o(this).attr('lay-filter');
			form.on('select(' + filter + ')', function(data) {
				var param = {};
				// 如果设置了逐步加载子级，每次点击异步获取子级数据
				e.is_step && e.url ? (param[e.primary_key] = data.value, t.ajax(e.url, function(res) {
					t.childs(e, res, data);
				}, param)) : (param = e.data[data.value] || {}, t.childs(e, param, data));
				// 事件监听
				layui.event.call(data.elem, MOD_NAME, MOD_NAME + '(' + filter + ')', data);
			})
		})
	}
	i.prototype.childs = function(e, data, ob) {
		var pt = o(ob.elem).parents('.' + e.zc),
			rt = pt.nextAll();
		// 如果没有子级了，删除后面子级元素，否则删除子级选项
		((data && data.length) || !ob.value ? rt.find('select option:not(:first)') : rt).remove();
		// 子级存在，处理子孙级元素
		if(data && data.length) {
			var k = o(e.elem).find('.' + e.zc).index(pt);
			// 如果下一级元素存在，直接往里面添加子级选项，否则追加子级元素
			if(pt.next().length) {
				pt.next().find('select').append(this.options(e, data, k));
			} else {
				// 如果设置了层级数，如果满足了层级数，不再往后追加元素
				if(e.count > 0 && o(e.elem).find('.' + e.zc).length == e.count) {
					return false;
				}
				pt.after(this.selects(e, data, k));
			}
		}
		form.render('select', o(e.elem).attr('lay-filter')), this.click(e);
	}
	i.prototype.selects = function(e, data, k) {
		var ih = '<div class="' + e.zc + ' ' + e.ic + '">';
		ih += '<select lay-filter="s_' + e.filter + '" name="' + (e.name[k + 1] || e.dname + '[]') + '">';
		ih += '<option value="">' + (e.hint[k + 1] || e.dhint) + '</option>' + this.options(e, data, k);
		ih += '</select></div>';
		return ih;
	}
	i.prototype.options = function(e, data, k) {
		var ih = '';
		o.each(data, function(index, item) {
			// 设置选中项selected
			var sh = e.selected[k + 1] && e.selected[k + 1] == item[e.primary_key] ? 'selected' : '';
			ih += '<option value="' + item[e.primary_key] + '" ' + sh + '>' + item[e.title_key] + '</option>';
		})
		return ih;
	}
	// 重新组装数据，明确父子级关系
	i.prototype.lists = function(data) {
		var result = [];
		o.each(data, function(index, item) {
			if(!result[item.pid]) {
				result[item.pid] = []
			}
			result[item.pid].push(item)
		});
		return result;
	}
	i.prototype.render = function(e) {
		var t = this;
		// 重定义配置参数覆盖默认配置
		e = o.extend(this.config(), e || {});
		// 初始化模板
		if(e.url) {
			t.ajax(e.url, function(res) {
				e.data = e.is_step ? res : t.lists(res), t.template(e);
			})
		} else {
			e.is_step && (e.data = this.lists(e.data)), this.template(e);
		}
	}
	i.prototype.ajax = function(url, callback, data) {
		o.ajax({
			url: url,
			type: 'GET',
			data: data || {},
			success: function(res) {
				callback && callback(res);
			}
		})
	}
	exports(MOD_NAME, new i())
})