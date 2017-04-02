<?php
namespace Core\Process;

/**
 * Class VenueManager
 * @package Core\Process
 */
class VenueManager extends Base
{
    /**
     * @var string
     */
    static $create_venue = "CREATE TABLE 
        venue(
            id int(11) NOT NULL auto_increment,
            name text,
            PRIMARY KEY (id)
        )";

    /**
     * @var string
     */
    static $create_space = "CREATE TABLE space (
        id int(11) NOT NULL auto_increment,
        venue int(11) DEFAULT  NULL,
        name text,
        PRIMARY KEY (id)
    )";

    /**
     * @var string
     */
    static $create_event = "CREATE TABLE event (
        id int(11) NOT NULL auto_increment,
        venue int(11) DEFAULT NULL,
        start mediumtext,
        duration int(11) DEFAULT NULL,
        name text,
        PRIMARY KEY (id)
    )";

    static $add_venue  = "INSERT INTO venue (name) VALUES (?)";
    static $add_space  = "INSERT INTO space (name, venue) VALUES (?, ?)";
    static $check_spot = "SELECT id, name FROM event WHERE space = ? AND (start + duration) > ? AND start < ?";
    static  $add_event = "INSERT into event (name, space, start, duration) values (?,?,?,?)";

    public function __construct()
    {
        parent::__construct();
        $this->installSpaceTable();
        $this->installVenueTable();
        $this->installEventTable();
    }



    /**
     * Install venue table
     *
     * @return bool
     */
    public function installVenueTable()
    {
        if (!$this->tableExists('venue')) {
            $this->doStatement(self::$create_venue,[]);
            return true;
        }
        return false;
    }

    /**
     * Install spaces.
     */
    public function installSpaceTable()
    {
        if (!$this->tableExists('space')) {
            $this->doStatement(self::$create_space,[]);
            return true;
        }
        return false;
    }

    /**
     * Install events.
     */
    public function installEventTable()
    {
        if (!$this->tableExists('event')) {
            $this->doStatement(self::$create_event,[]);
            return true;
        }
        return false;
    }

    /**
     * Add a concrete venue
     *
     * @param $name
     * @param $space_array
     * @return array
     */
    public function addVenue($name, $space_array)
    {
        $venuesdata = [];
        $venuesdata["venue"] = [$name];
        $this->doStatement(self::$add_venue, $venuesdata['venue']);
        $v_id = self::$DB->lastInsertId();
        $venuesdata['spaces'] = [];
        foreach ($space_array as $space_name) {
            $values = [$space_name, $v_id];
            $this->doStatement(self::$add_space, $values);
            $s_id = self::$DB->lastInsertId();
            array_unshift($values, $s_id);
            $venuesdata['space'][] = $values;
        }
        return $venuesdata;
    }

    /**
     * Book event
     *
     * @param $space_id
     * @param $name
     * @param $time
     * @param $duration
     * @throws \Core\Exception\Base
     */
    public function bookEvent($space_id, $name, $time, $duration)
    {
        $values = [$space_id, $time, ($time + $duration)];
        $stmt = $this->doStatement(self::$check_spot, $values, false);
        if ($result = $stmt->fetch()) {
            throw new \Core\Exception\Base("Have been already registered");
        }
        $this->doStatement(self::$add_event, [$name, $space_id, $time, $duration]);
    }
}
