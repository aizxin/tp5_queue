<?php
namespace app\index\controller;
use think\Exception;

use think\Queue;
use Nette\Mail\Message;
use think\Log;

class Index
{
    public function index()
    {
        // 1.当前任务将由哪个类来负责处理。
        //   当轮到该任务时，系统将生成一个该类的实例，并调用其 fire 方法
        $jobHandlerClassName  = 'app\index\job\Hello';
        // // 2.当前任务归属的队列名称，如果为新队列，会自动创建
        $jobQueueName       = "helloJobQueue";
        // // // 3.当前任务所需的业务数据 . 不能为 resource 类型，其他类型最终将转化为json形式的字符串
        // // //   ( jobData 为对象时，需要在先在此处手动序列化，否则只存储其public属性的键值对)
        $jobData  = [ 'userEmail' => "2741615638@qq.com", 'title' => "类型最终将转化为json形式" , 'contents' => "obData 为对象时，需要在先在此处手动序列化，否则只存储其public属性的键值对" ] ;
        // // 4.将该任务推送到消息队列，等待对应的消费者去执行
        // $isPushed = Queue::push( $jobHandlerClassName , $jobData , $jobQueueName );
        $isPushed = Queue::later(60, $jobHandlerClassName , $jobData , $jobQueueName );
        // database 驱动时，返回值为 1|false  ;   redis 驱动时，返回值为 随机字符串|false
        if( $isPushed !== false ){
            echo date('Y-m-d H:i:s') . " a new Hello Job is Pushed to the MQ"."<br>";
        }else{
            echo 'Oops, something went wrong.';
        }
        // 根据消息中的数据进行实际的业务处理...
        // $mail = new Message;
        // $mail->setFrom('PHP实战课程-高价值的PHP API <wmk223@163.com>')
        //     ->addTo( $jobData['userEmail'] )
        //     ->setSubject( $jobData['title'] )
        //     ->setBody( $jobData['contents'] );

        // $mailer = new \Nette\Mail\SmtpMailer([
        //         'host' => 'smtp.163.com',
        //         'username' => 'wmk223@163.com',
        //         'password' => 'Mn456123mN66', /* smtp独立密码 */
        //         // 'secure' => 'ssl',
        // ]);
        // $rep = $mailer->send($mail);

	// 	$mailer = new \Nette\Mail\SmtpMailer([
    //         'host' => 'smtp.126.com',
    //         'username' => 'imooc_phpapi@126.com',
    //         'password' => 'phpapi321', /* smtp独立密码 */
    //         // 'secure' => 'ssl',
    // ]);
    // $rep = $mailer->send($mail);
        // var_dump($rep);
        
        // var_dump($jobData);
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ad_bd568ce7058a1091"></think>';
    }
}
