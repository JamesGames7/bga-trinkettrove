<?php
declare(strict_types=1);

namespace Bga\Games\TrinketTroveTest\States;

use Bga\GameFramework\Actions\Types\IntArrayParam;
use Bga\GameFramework\StateType;
use Bga\GameFramework\States\GameState;
use Bga\GameFramework\States\PossibleAction;
use Bga\Games\TrinketTroveTest\Game;
use Bga\GameFramework\UserException;

class MakeBid extends GameState
{
    function __construct(
        protected Game $game,
    ) {
        parent::__construct($game,
            id: 20,
            type: StateType::ACTIVE_PLAYER,

            // optional
            description: clienttranslate('${actplayer} must make their bid'),
            descriptionMyTurn: clienttranslate('${you} must make your bid (0 selected)'),
            transitions: ["nextPlayer" => 20, "drafting" => 30],
            updateGameProgression: false,
            initialPrivate: null,
        );
    }

    public function getArgs(): array
    {
        // the data sent to the front when entering the state
        return [];
    }

    function onEnteringState(int $activePlayerId) {
        // the code to run when entering the state
        
    }

    #[PossibleAction]
    public function actMakeBid(int $activePlayerId, #[IntArrayParam()] array $cards) {
        foreach ($cards as $id) {
            $card = $this->game->cards->getCard($id);

            if (!($card["location"] == "hand" && $card["location_arg"] == $activePlayerId)) {
                throw new UserException("Error with card selection. Please submit a bug report and reload the page.");
            }
        }
    }

    function zombie(int $playerId): string {
        // the code to run when the player is a Zombie
        return "";
    }
}