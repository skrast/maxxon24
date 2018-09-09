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
        
        $result = true;
        
        if (!$this->user)
            $this->user = !isset($params['user']) ? get_user_info() : $params['user'];
        
            switch ($this->action) {
            
            //Orders
            case ActionEnum::OrdersCreate:
                if (is_callable($this->callback))
                    $result = ($this->callback)($params);
                 else
                    $result = self::onOrdersCreate($params);
            break;
                        
            case ActionEnum::OrdersList:
                if (is_callable($this->callback))
                    $result = ($this->callback)($params);
                else
                    $result = self::onOrdersList($params);
            break;
                        
            case ActionEnum::OrdersEdit:
                if (is_callable($this->callback))
                    $result = ($this->callback)($params);
                else
                    $result = self::onOrdersEdit($params);
            break;
                        
            case ActionEnum::OrdersDelete:
                if (is_callable($this->callback))
                    $result = ($this->callback)($params);
                else
                    $result = self::onOrdersDelete($params);
            break;
            
            // Jobs            
            case ActionEnum::JobsCreate:
                if (is_callable($this->callback))
                    $result = ($this->callback)($params);
                else
                    $result = self::onJobsCreate($params);
            break;
                        
            case ActionEnum::JobsList:
                if (is_callable($this->callback))
                    $result = ($this->callback)($params);
                else
                    $result = self::onJobsList($params);
            break;
                        
            case ActionEnum::JobsEdit:
                if (is_callable($this->callback))
                    $result = ($this->callback)($params);
                else
                    $result = self::onOrdersEdit($params);
            break;
                        
            case ActionEnum::JobsDelete:
                if (is_callable($this->callback))
                    $result = ($this->callback)($params);
                else
                    $result = self::onJobsDelete($params);
            break;
            // Resumes
            case ActionEnum::ResumesCreate:
                if (is_callable($this->callback))
                    $result = ($this->callback)($params);
                else
                    $result = self::onResumesCreate($params);
            break;
                        
            case ActionEnum::ResumesList:
                if (is_callable($this->callback))
                    $result = ($this->callback)($params);
                else
                    $result = self::onResumesList($params);
            break;
                        
            case ActionEnum::ResumesEdit:
                if (is_callable($this->callback))
                    $result = ($this->callback)($params);
                 else
                    $result = self::onResumesEdit($params);
           break;
                        
            case ActionEnum::ResumesDelete:
                if (is_callable($this->callback))
                    $result = ($this->callback)($params);
                else
                    $result = self::onResumesDelete($params);
           break;
                        
           // Services
            case ActionEnum::ServicesCreate:
                if (is_callable($this->callback))
                    $result = ($this->callback)($params);
                else
                    $result = self::onServicesCreate($params);
            break;
                        
            case ActionEnum::ServicesList:
                if (is_callable($this->callback))
                    $result = ($this->callback)($params);
                else
                    $result = self::onServicesList($params);
           break;
                        
            case ActionEnum::ServicesEdit:
                if (is_callable($this->callback))
                    $result = ($this->callback)($params);
                else
                    $result = self::onServicesEdit($params);
           break;
                        
            case ActionEnum::ServicesDelete:
                if (is_callable($this->callback))
                    $result = ($this->callback)($params);
                else
                    $result = self::onServicesDelete($params);
            break;
            
            default:
                if (is_callable($this->callback))
                    $result = ($this->callback)($params);
                else
                    throw new Exception('Uknown action to handle !');
            break;
        }
        
        return $result;
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
            return false;
        } 
        return true;
    }
    private function onOrdersCreate($params=[]) {

        // if it's a user with basic subscription
        if (in_array($this->user->user_billing, [1])) {
            twig::assign('message', twig::$lang['change_subscription']);
            $this->render_acl_template();
            return false;
        } 
        // if a user subscription is optima and he already has 5 orders in the current month;
        else if (
            $this->user->user_billing == 2 &&
            $this->user->aux_user_orders_current_month >= 5)
        {
            twig::assign('message', twig::$lang['aux_user_orders_current_month']);
            $this->render_acl_template();
            return false;
        }
        
        return true;
            
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
            twig::assign('message', twig::$lang['change_subscription']);
            $this->render_acl_template();
            return false;
        }
        return true;
               
    }
    private function onJobsCreate($params=[]) {

        // if it's a user with basic subscription
        if (in_array($this->user->user_billing, [1])) {
            twig::assign('message', twig::$lang['change_subscription']);
            $this->render_acl_template();
            return false;
        }
        // if a user subscription is optima and he already has 5 orders in the current month;
        else if (
            $this->user->user_billing == 2 &&
            $this->user->aux_user_jobs_count >= 3)
        {
            twig::assign('message', twig::$lang['aux_user_jobs_count']);
            $this->render_acl_template();
            return false;
        }
        return true;
               
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
            twig::assign('message', twig::$lang['change_subscription']);
            $this->render_acl_template();
            return false;
        } 
        
        return true;
        
    }
    private function onResumesCreate($params=[]) {
        
        // if it's a user with basic subscription
        if (in_array($this->user->user_billing, [1])) {
            twig::assign('message', twig::$lang['change_subscription']);
            $this->render_acl_template();
            return false;
        }
        // if a user subscription is optima and he already has 3 resumes;
        else if (
            $this->user->user_billing == 2 &&
            $this->user->aux_user_resumes_count >= 3)
        {
            twig::assign('message', twig::$lang['aux_user_resumes_count']);
            $this->render_acl_template();
            return false;
        }
        
        return true;
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
        
        
        if ($this->user->user_billing == 2 &&
            ($this->user->aux_user_service_count > 3 || $this->user->aux_user_service_count_temp > 3))
        {
            twig::assign('message', twig::$lang['aux_user_service_count3']);
            $this->render_acl_template();
            return false;
        } else 
        if ($this->user->user_billing == 1 &&
            ($this->user->aux_user_service_count > 1 || $this->user->aux_user_service_count_temp > 1))
        {
            twig::assign('message', twig::$lang['aux_user_service_count1']);
            $this->render_acl_template();
            return false;
        }
            
        return true;
    }
    private function onServicesEdit($params=[]) {
        throw new Exception("onServicesEdit not implemented");
    }
    private function onServicesDelete($params=[]) {
        throw new Exception("onServicesDelete not implemented");
    }
    
}

?>