<?php 
namespace Models;

class Room{
    private $roomId;
    private $roomName;
    private $roomCapacity;
    private $is3D;
    private $roomTicketPrice;
    private $cinema;
    private $availability;

    public function __construct()
    {

    }




    /**
     * Get the value of roomId
     */ 
    public function getRoomId()
    {
        return $this->roomId;
    }

    /**
     * Set the value of roomId
     *
     * @return  self
     */ 
    public function setRoomId($roomId)
    {
        $this->roomId = $roomId;

        return $this;
    }

    /**
     * Get the value of roomName
     */ 
    public function getRoomName()
    {
        return $this->roomName;
    }

    /**
     * Set the value of roomName
     *
     * @return  self
     */ 
    public function setRoomName($roomName)
    {
        $this->roomName = $roomName;

        return $this;
    }

    /**
     * Get the value of roomCapacity
     */ 
    public function getRoomCapacity()
    {
        return $this->roomCapacity;
    }

    /**
     * Set the value of roomCapacity
     *
     * @return  self
     */ 
    public function setRoomCapacity($roomCapacity)
    {
        $this->roomCapacity = $roomCapacity;

        return $this;
    }

    /**
     * Get the value of is3D
     */ 
    public function getIs3D()
    {
        return $this->is3D;
    }

    /**
     * Set the value of is3D
     *
     * @return  self
     */ 
    public function setIs3D($is3D)
    {
        $this->is3D = $is3D;

        return $this;
    }

    /**
     * Get the value of roomTicketPrice
     */ 
    public function getroomPrice()
    {
        return $this->roomTicketPrice;
    }
    public function setRoomCinema($cinema)
    {
        $this->cinema = $cinema;
    }
   public function getRoomCinema()
    {
        return $this->cinema;
    }
    public function setRoomAvailability($roomAvailability)
    {
        $this->availability = $roomAvailability;
    }
   public function getRoomAvailability()
    {
        return $this->availability;
    }

    /**
     * Set the value of roomTicketPrice
     *
     * @return  self
     */ 
    public function setroomPrice($roomTicketPrice)
    {
        $this->roomTicketPrice = $roomTicketPrice;

        return $this;
    }
}