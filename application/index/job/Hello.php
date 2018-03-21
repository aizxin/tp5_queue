<?php
namespace app\index\job;

use think\queue\Job;
use think\Log;
use Nette\Mail\Message;
use Nette\Mail\SmtpMailer;

class Hello
{
   /**
    * fire方法是消息队列默认调用的方法
    * @param Job            $job      当前的任务对象
    * @param array|mixed    $data     发布任务时自定义的数据
    */
    public function fire(Job $job,$data){
        $isJobDone = $this->doHelloJob($data);

        if ($isJobDone) {
            //如果任务执行成功， 记得删除任务
            $job->delete();
        }else{
            if ($job->attempts() > 3) {
                //通过这个方法可以检查这个任务已经重试了几次了
                    $job->delete();
                // 也可以重新发布这个任务
                //print("<info>Hello Job will be availabe again after 2s."."</info>\n");
                //$job->release(2); //$delay为延迟时间，表示该任务延迟2秒后再执行
            }
        }
    }
    /**
    * 根据消息中的数据进行实际的业务处理
    * @param array|mixed    $data     发布任务时自定义的数据
    * @return boolean                 任务执行的结果
    */
    public function doHelloJob($data) {
        // 根据消息中的数据进行实际的业务处理...
        $mail = new Message;
        $mail->setFrom('PHP实战课程-高价值的PHP API <wmk223@163.com>')
            ->addTo( $data['userEmail'] )
            ->setSubject( $data['title'] )
            ->setBody( $data['contents'] );

        $mailer = new SmtpMailer([
            'host' => 'smtp.163.com',
            'username' => 'wmk223@163.com',
            'password' => 'Mn456123mN66', /* smtp独立密码 */
                // 'secure' => 'ssl',
        ]);
        $rep = $mailer->send($mail);
        return true;
    }
    /**
     * 该方法用于接收任务执行失败的通知，你可以发送邮件给相应的负责人员
     * @param $jobData  string|array|...      //发布任务时传递的 jobData 数据
     */
    public function failed($jobData){
        // send_mail_to_somebody() ;
        Log::info('ffffs'.json_encode($jobData));
    }
}
