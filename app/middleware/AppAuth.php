<?php
declare (strict_types=1);

namespace app\middleware;

class AppAuth {
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, \Closure $next) {
        $check = checkSign();
        if ($check !== true) {
            return (json($check));
        }
        return $next($request);
    }
}
