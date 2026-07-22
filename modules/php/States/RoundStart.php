<?php
declare(strict_types=1);

namespace Bga\Games\TrinketTroveTest\States;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\GameState;
use Bga\Games\TrinketTroveTest\Game;

class RoundStart extends GameState
{
    function __construct(
        protected Game $game,
    ) {
        parent::__construct($game,
            id: 2,
            type: StateType::GAME,

            // optional
            description: clienttranslate('Setting up for the round'),
            transitions: [],
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
        for ($i = 0; $i < $this->game->getPlayersNumber(); $i++) {
            $this->game->cards->pickCardForLocation("deck", "market", $i);

            $this->notify->all("marketAdded", '${name} was added to the market', [
                "name" => $this->game->cardList[array_values($this->game->cards->getCardsInLocation("market", $i))[0]["type_arg"]]->getName()
            ]);
        }

        // TODO more people
        $this->game->cards->pickCardForLocation("timer", "temp");
        $card = $this->game->convertCards($this->game->cards->getCardsInLocation("temp"));

        $this->game->cards->moveAllCardsInLocation("temp", "market");
        $this->notify->all("marketAdded", '${name} was added to the market timer', [
            "name" => $card[0]["name"]
        ]);
    }
}