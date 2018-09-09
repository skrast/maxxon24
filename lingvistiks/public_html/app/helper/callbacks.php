<?php

abstract class ActionBaseEnum {}
abstract class ActionEnum extends ActionBaseEnum {
    
    const OrdersList        = 'orders.list';
    const OrdersCreate      = 'orders.create';
    const OrdersEdit        = 'orders.edit';
    const OrdersDelete      = 'orders.delete';

    const JobsList          = 'jobs.list';
    const JobsCreate        = 'jobs.create';
    const JobsEdit          = 'jobs.edit';
    const JobsDelete        = 'jobs.delete';
    
    const ResumesList       = 'resumes.list';
    const ResumesCreate     = 'resumes.create';
    const ResumesEdit       = 'resumes.edit';
    const ResumesDelete     = 'resumes.delete';

    const ServicesList       = 'services.list';
    const ServicesCreate     = 'services.create';
    const ServicesEdit       = 'services.edit';
    const ServicesDelete     = 'services.delete';
    
    
}

class CallBackHelper
{
    const tpl_change_subscription = '_change_subscription.tpl';
    
    private $props = array();
    
    function __construct() {
        $this->user = func_get_arg(0);
    }
    
    public function __set($name, $value)
    {
        $this->props[$name] = $value;
    }
    public function __get($name)
    {
        if (array_key_exists($name, $this->props)) {
            return $this->props[$name];
        }
    }
    
    public function __invoke()
    {
        $this->action = func_get_arg(0);
        $this->callback = func_get_arg(1);
        $params = func_get_arg(2);
        
        if (!$this->user)
            $this->user = !isset($params['user']) ? get_user_info() : $params['user'];
        
            switch ($this->action) {
            
            //Orders
            case ActionEnum::OrdersCreate:
                if ($function)
                    $function($params);
                 else
                     self::onOrdersCreate($params);
            break;
                        
            case ActionEnum::OrdersList:
                if ($function)
                    $function($action, $this->user);
                else
                    self::onOrdersList();
            break;
                        
            case ActionEnum::OrdersEdit:
                if ($function)
                    $function($params);
                else
                    self::onOrdersEdit($params);
            break;
                        
            case ActionEnum::OrdersDelete:
                if ($function)
                    $function($params);
                else
                    self::onOrdersDelete($params);
            break;
            
            // Jobs            
            case ActionEnum::JobsCreate:
                if ($function)
                    $function($params);
                else
                    self::onJobsCreate($params);
            break;
                        
            case ActionEnum::JobsList:
                if ($function)
                    $function($params);
                    else
                        self::onJobsList($params);
            break;
                        
            case ActionEnum::JobsEdit:
                if ($function)
                    $function($params);
                    else
                        self::onOrdersEdit($params);
            break;
                        
            case ActionEnum::JobsDelete:
                if ($function)
                    $function($params);
                    else
                        self::onJobsDelete($params);
            break;
            // Resumes
            case ActionEnum::ResumesCreate:
                if ($function)
                    $function($params);
                    else
                        self::onResumesCreate($params);
            break;
                        
            case ActionEnum::ResumesList:
                if ($function)
                    $function($params);
                    else
                        self::onResumesList($params);
            break;
                        
            case ActionEnum::ResumesEdit:
                if ($function)
                    $function($params);
                 else
                    self::onResumesEdit($params);
           break;
                        
            case ActionEnum::ResumesDelete:
                if ($function)
                    $function($params);
                else
                    self::onResumesDelete($params);
           break;
                        
           // Services
            case ActionEnum::ServicesCreate:
                if ($function)
                    $function($params);
                else
                    self::onServicesCreate($params);
            break;
                        
            case ActionEnum::ServicesList:
                if ($function)
                    $function($params);
                else
                    self::onServicesList($params);
           break;
                        
            case ActionEnum::ServicesEdit:
                if ($function)
                    $function($params);
                else
                    self::onServicesEdit($params);
                break;
                        
            case ActionEnum::ServicesDelete:
                if ($function)
                    $function($params);
                else
                    self::onServicesDelete($params);
            break;
            
            default:
                if ($function)
                    $function($params);
                else
                    throw new Exception('Uknown action to handle !');
            break;
        }
    }
    
    private function render_acl_template(){
        
        $html = twig::fetch('frontend/chank/perfomens_col.tpl');
        twig::assign('perfomens_col', $html);
        twig::assign('content', twig::fetch('frontend/' . self::tpl_change_subscription));
    }
    /* Orders methods */
    private function onOrdersList($params=[]) {      
        
        // if it's a user with basic subscription
        if (in_array($this->user->user_billing, [1])) {
            twig::assign('message', twig::$lang['change_subscription']);
            $this->render_acl_template();
        } else {
            twig::assign('content', twig::fetch('frontend/order_list.tpl'));
        }
    }
    private function onOrdersCreate($params=[]) {

        // if it's a user with basic subscription
        if (in_array($this->user->user_billing, [1])) {
            twig::assign('message', twig::$lang['change_subscription']);
            $this->render_acl_template();
        } 
        // if a user subscription is optima and he already has 5 orders in the current month;
        else if (
            $this->user->user_billing == 2 &&
            $this->user->aux_user_orders_current_month >= 5)
        {
            twig::assign('message', twig::$lang['aux_user_orders_current_month']);
            $this->render_acl_template();
        } else {
            twig::assign('content', twig::fetch('frontend/owner_order_edit.tpl'));
        }
            
    }
    private function onOrdersEdit($params=[]){
       throw new Exception("onOrdersEdit not implemented");
    }
    private function onOrdersDelete($params=[]){
        throw new Exception("onOrdersDelete not implemented");
    }

    /* Jobs methods */
    private function onJobsList($params=[]) {
  
        // if it's a user with a basic subscription
        if (in_array($this->user->user_billing, [1])) {
            $this->render_acl_template();
        } else {
            twig::assign('content', twig::fetch('frontend/jobs_list.tpl'));
        }
               
    }
    private function onJobsCreate($params=[]) {

        // if it's a user with basic subscription
        if (in_array($this->user->user_billing, [1])) {
            $this->render_acl_template();
        }
        // if a user subscription is optima and he already has 5 orders in the current month;
        else if (
            $this->user->user_billing == 2 &&
            $this->user->aux_user_jobs_count >= 3)
        {
            twig::assign('message', twig::$lang['aux_user_jobs_count']);
            $this->render_acl_template();
        } else {
            twig::assign('content', twig::fetch('frontend/owner_order_edit.tpl'));
        }
               
    }
    private function onJobsEdit($params=[]) {
        throw new Exception("onJobsEdit not implemented");
    }
    private function onJobsDelete($params=[]) {
        throw new Exception("onJobsDelete not implemented");      
    }
    /* Resumes methods */
    private function onResumesList($params=[]) {
     
        // if it's a user with a basic subscription
        if (in_array($this->user->user_billing, [1])) {
            $this->render_acl_template();
        } else {
            twig::assign('content', twig::fetch('frontend/resume_list.tpl'));
        }
        
    }
    private function onResumesCreate($params=[]) {
        

        // if it's a user with basic subscription
        if (in_array($this->user->user_billing, [1])) {
            $this->render_acl_template();
        }
        // if a user subscription is optima and he already has 3 resumes;
        else if (
            $this->user->user_billing == 2 &&
            $this->user->aux_user_resumes_count >= 3)
        {
            twig::assign('message', twig::$lang['aux_user_resumes_count']);
            $this->render_acl_template();
        } else {
            twig::assign('content', twig::fetch('frontend/perfomens_resume_open.tpl'));
        }
        
        
    }
    private function onResumesEdit($params=[]) {
        throw new Exception("onResumesEdit not implemented");
    }
    private function onResumesDelete($params=[]) {
        throw new Exception("onResumesDelete not implemented");
    }
    
    // Services methods
    private function onServicesList($params=[]) {
        throw new Exception("onServicesList not implemented");
    }
    private function onServicesCreate($params=[]) {
        
        if (
            $this->user->user_billing == 1 &&
            $this->user->aux_user_service_count >= 1)
        {
            twig::assign('message', twig::$lang['aux_user_service_count1']);
            $this->render_acl_template();
        } else  if (
            $this->user->user_billing == 2 &&
            $this->user->aux_user_jobs_count >= 3)
        {
            twig::assign('message', twig::$lang['aux_user_service_count3']);
            $this->render_acl_template();
        } else {
            twig::assign('content', twig::fetch('frontend/owner_order_edit.tpl'));
        }
    }
    private function onServicesEdit($params=[]) {
        throw new Exception("onServicesEdit not implemented");
    }
    private function onServicesDelete($params=[]) {
        throw new Exception("onServicesDelete not implemented");
    }
    
    
}

?>