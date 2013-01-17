<?php

/**
 * This behavior allows to attach an event to whole ActiveRecord class
 * @author urmaul
 */
class ModelEventsBehavior extends CActiveRecordBehavior
{
    public $ownerClass = null;
    
    /**
     * @var boolean
     */
    public $raiseAfterDelete = false;
    
    /**
     * Rasies an event attached to model singleton
     * @param string $name
     * @param CEvent $event
     */
    public function raiseModelEvent($name, $event = null)
    {
        $ownerClass = $this->_getOwnerClass();
        
        if (!isset($event))
            $event = new CEvent($this->getOwner());
        
        CActiveRecord::model($ownerClass)
            ->raiseEvent($name, $event);
    }
    
    /**
     * Retuns owner class
     * @return string
     */
    private function _getOwnerClass()
    {
        if (!isset($this->ownerClass))
            $this->ownerClass = get_class($this->getOwner());
        
        return $this->ownerClass;
    }
    
    /**
     * (non-PHPdoc)
     * @see CActiveRecordBehavior::afterDelete()
     */
    public function afterDelete($event)
    {
        if ($this->raiseAfterDelete && $event->sender === $this->getOwner())
            $this->raiseModelEvent('onAfterDelete', $event);
    }
}
