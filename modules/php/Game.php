<?php
/**
 *------
 * BGA framework: Gregory Isabelli & Emmanuel Colin & BoardGameArena
 * TrinketTroveTest implementation : © Connor Rask connor@srask.ca
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * Game.php
 *
 * This is the main file for your game logic.
 *
 * In this PHP file, you are going to defines the rules of the game.
 */
declare(strict_types=1);

namespace Bga\Games\TrinketTroveTest;

use Bga\Games\TrinketTroveTest\States\RoundStart;
use Bga\GameFramework\Components\Deck;
use Card;

require('Card.php');

class Game extends \Bga\GameFramework\Table
{

    public Deck $cards;
    public array $cardList;

    /**
     * Your global variables labels:
     *
     * Here, you can assign labels to global variables you are using for this game. You can use any number of global
     * variables with IDs between 10 and 99. If you want to store any type instead of int, use $this->globals instead.
     *
     * NOTE: afterward, you can get/set the global variables with `getGameStateValue`, `setGameStateInitialValue` or
     * `setGameStateValue` functions.
     */
    public function __construct()
    {
        parent::__construct();

        $this->cards = $this->deckFactory->createDeck('cards');

        $this->cardList = [
            new Card("Bell", 2, [10, 30], 6),
            new Card("Bell", 2, [10, 30], 21),
            new Card("Thimble", 2, [5, 35], 20),
            new Card("Thimble", 2, [5, 35], 35),
            new Card("Gear", 3, [5, 20, 55], 4),
            new Card("Gear", 3, [5, 20, 55], 33),
            new Card("Gear", 3, [5, 20, 55], 46),
            new Card("Button", 3, [10, 25, 45], 7),
            new Card("Button", 3, [10, 25, 45], 14),
            new Card("Button", 3, [10, 25, 45], 34),
            new Card("Key", 4, [10, 30, 30, 70], 3),
            new Card("Key", 4, [10, 30, 30, 70], 28),
            new Card("Key", 4, [10, 30, 30, 70], 31),
            new Card("Key", 4, [10, 30, 30, 70], 44),
            new Card("Shell", 4, [10, 20, 40, 60], 13),
            new Card("Shell", 4, [10, 20, 40, 60], 32),
            new Card("Shell", 4, [10, 20, 40, 60], 38),
            new Card("Shell", 4, [10, 20, 40, 60], 45),
            new Card("Bottlecap", 5, [5, 5, 5, 50, 90], 8),
            new Card("Bottlecap", 5, [5, 5, 5, 50, 90], 11),
            new Card("Bottlecap", 5, [5, 5, 5, 50, 90], 25),
            new Card("Bottlecap", 5, [5, 5, 5, 50, 90], 42),
            new Card("Bottlecap", 5, [5, 5, 5, 50, 90], 53),
            new Card("Marble", 5, [5, 10, 20, 40, 80], 12),
            new Card("Marble", 5, [5, 10, 20, 40, 80], 18),
            new Card("Marble", 5, [5, 10, 20, 40, 80], 30),
            new Card("Marble", 5, [5, 10, 20, 40, 80], 43),
            new Card("Marble", 5, [5, 10, 20, 40, 80], 54),
            new Card("Gem", 6, [10, 25, 25, 60, 60, 125], 2),
            new Card("Gem", 6, [10, 25, 25, 60, 60, 125], 5),
            new Card("Gem", 6, [10, 25, 25, 60, 60, 125], 37),
            new Card("Gem", 6, [10, 25, 25, 60, 60, 125], 40),
            new Card("Gem", 6, [10, 25, 25, 60, 60, 125], 51),
            new Card("Gem", 6, [10, 25, 25, 60, 60, 125], 57),
            new Card("Crayon", 6, [20, 10, 0, 80, 100, 120], 10),
            new Card("Crayon", 6, [20, 10, 0, 80, 100, 120], 15),
            new Card("Crayon", 6, [20, 10, 0, 80, 100, 120], 41),
            new Card("Crayon", 6, [20, 10, 0, 80, 100, 120], 47),
            new Card("Crayon", 6, [20, 10, 0, 80, 100, 120], 52),
            new Card("Crayon", 6, [20, 10, 0, 80, 100, 120], 58),
            new Card("Lure", 7, [5, 10, 15, 20, 25, 80, 175], 0),
            new Card("Lure", 7, [5, 10, 15, 20, 25, 80, 175], 9),
            new Card("Lure", 7, [5, 10, 15, 20, 25, 80, 175], 17),
            new Card("Lure", 7, [5, 10, 15, 20, 25, 80, 175], 23),
            new Card("Lure", 7, [5, 10, 15, 20, 25, 80, 175], 26),
            new Card("Lure", 7, [5, 10, 15, 20, 25, 80, 175], 48),
            new Card("Lure", 7, [5, 10, 15, 20, 25, 80, 175], 55),
            new Card("Feather", 7, [5, 10, 25, 50, 80, 110, 145], 1),
            new Card("Feather", 7, [5, 10, 25, 50, 80, 110, 145], 19),
            new Card("Feather", 7, [5, 10, 25, 50, 80, 110, 145], 24),
            new Card("Feather", 7, [5, 10, 25, 50, 80, 110, 145], 27),
            new Card("Feather", 7, [5, 10, 25, 50, 80, 110, 145], 36),
            new Card("Feather", 7, [5, 10, 25, 50, 80, 110, 145], 50),
            new Card("Feather", 7, [5, 10, 25, 50, 80, 110, 145], 56),
            new Card("Mirror", 8, [], 16),
            new Card("Mirror", 8, [], 22)
        ];
    }

    /**
     * Compute and return the current game progression.
     *
     * The number returned must be an integer between 0 and 100.
     *
     * This method is called each time we are in a game state with the "updateGameProgression" property set to true.
     *
     * @return int
     */
    public function getGameProgression()
    {
        // TODO: compute and return the game progression

        return 0;
    }

    /**
     * Migrate database.
     *
     * You don't have to care about this until your game has been published on BGA. Once your game is on BGA, this
     * method is called everytime the system detects a game running with your old database scheme. In this case, if you
     * change your database scheme, you just have to apply the needed changes in order to update the game database and
     * allow the game to continue to run with your new version.
     *
     * @param int $from_version
     * @return void
     */
    public function upgradeTableDb($from_version)
    {
//       if ($from_version <= 1404301345)
//       {
//            // ! important ! Use `DBPREFIX_<table_name>` for all tables
//
//            $sql = "ALTER TABLE `DBPREFIX_xxxxxxx` ....";
//            $this->applyDbUpgradeToAllDB( $sql );
//       }
//
//       if ($from_version <= 1405061421)
//       {
//            // ! important ! Use `DBPREFIX_<table_name>` for all tables
//
//            $sql = "CREATE TABLE `DBPREFIX_xxxxxxx` ....";
//            $this->applyDbUpgradeToAllDB( $sql );
//       }
    }

    /*
     * Gather all information about current game situation (visible by the current player).
     *
     * The method is called each time the game interface is displayed to a player, i.e.:
     *
     * - when the game starts
     * - when a player refreshes the game page (F5)
     */
    protected function getAllDatas(int $currentPlayerId): array
    {
        $result = [];
        // WARNING: We must only return information visible by the current player (using $currentPlayerId).

        // Get information about players.
        // NOTE: you can retrieve some extra field you added for "player" table in `dbmodel.sql` if you need it.
        $result["players"] = $this->getCollectionFromDb(
            "SELECT `player_id` AS `id`, `player_score` AS `score` FROM `player`"
        );

        $result["market"] = [];
        for ($i = 0; $i < $this->getPlayersNumber(); $i++) {
            $result["market"][] = $this->convertCards($this->cards->getCardsInLocation("market", $i));
        }
        $result["timer"] = $this->cards->countCardsInLocation("timer");
        $result["hand"] = $this->convertCards($this->cards->getCardsInLocation("hand", $currentPlayerId));

        // TODO: Gather all information about current game situation (visible by player $currentPlayerId).

        return $result;
    }

    /**
     * This method is called only once, when a new game is launched. In this method, you must setup the game
     *  according to the game rules, so that the game is ready to be played.
     */
    protected function setupNewGame($players, $options = [])
    {
        // Set the colors of the players with HTML color code. The default below is red/green/blue/orange/brown. The
        // number of colors defined here must correspond to the maximum number of players allowed for the gams.
        $gameinfos = $this->getGameinfos();
        shuffle($gameinfos['player_colors']);
        $default_colors = $gameinfos['player_colors'];
        $query_values = [];

        foreach ($players as $player_id => $player) {
            // Now you can access both $player_id and $player array
            $query_values[] = vsprintf("(%s, '%s', '%s')", [
                $player_id,
                array_shift($default_colors),
                addslashes($player["player_name"]),
            ]);
        }

        // Create players based on generic information.
        //
        // NOTE: You can add extra field on player table in the database (see dbmodel.sql) and initialize
        // additional fields directly here.
        static::DbQuery(
            sprintf(
                "INSERT INTO `player` (`player_id`, `player_color`, `player_name`) VALUES %s",
                implode(",", $query_values)
            )
        );

        $this->reattributeColorsBasedOnPreferences($players, $gameinfos["player_colors"]);
        $this->reloadPlayersBasicInfos();

        // Init global values with their initial values.

        // Init game statistics.
        //
        // NOTE: statistics used in this file must be defined in your `stats.inc.php` file.

        // Dummy content.
        // $this->tableStats->init('table_teststat1', 0);
        // $this->playerStats->init('player_teststat1', 0);

        // TODO: Setup the initial game situation here.
        $cardsToWrite = [];
        $i = 0;
        foreach ($this->cardList as $card) {
            $cardsToWrite[] = [
                "type" => $card->getName(),
                "type_arg" => $i,
                "nbr" => 2
            ];
            $i++;
        }
        $this->cards->createCards($cardsToWrite, 'deck');
        
        $this->cards->moveCards(array_map(fn($card) => $card["id"], $this->cards->getCardsOfType("Mirror")), "temp");
        $this->cards->shuffle('deck');
        
        foreach ($players as $id => $info) {
            $this->cards->pickCards(4, "deck", $id);
        }

        $this->cards->moveAllCardsInLocation("temp", "deck");
        $this->cards->shuffle('deck');

        // TODO: 5+ people
        $this->cards->pickCardsForLocation(6, "deck", "timer");
        $this->cards->shuffle("timer");

        // Activate first player once everything has been initialized and ready.
        $this->activeNextPlayer();

        return RoundStart::class;
    }

    public function convertCards(array $cards) {
        return array_map(fn($card) => $this->cardList[$card["type_arg"]]->getInfo(intval($card["id"])), array_values($cards));
    }

    /**
     * Example of debug function.
     * Here, jump to a state you want to test (by default, jump to next player state)
     * You can trigger it on Studio using the Debug button on the right of the top bar.
     */
    public function debug_goToState(int $state = 3) {
        $this->gamestate->jumpToState($state);
    }

    /**
     * Another example of debug function, to easily test the zombie code.
     */
    public function debug_playOneMove() {
        $this->bga->debug->playUntil(fn(int $count) => $count == 1);
    }

    /*
    Another example of debug function, to easily create situations you want to test.
    Here, put a card you want to test in your hand (assuming you use the Deck component).

    public function debug_setCardInHand(int $cardType, int $playerId) {
        $card = array_values($this->cards->getCardsOfType($cardType))[0];
        $this->cards->moveCard($card['id'], 'hand', $playerId);
    }
    */
}
