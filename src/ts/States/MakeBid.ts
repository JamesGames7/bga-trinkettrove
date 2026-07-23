import { Game } from "../Game";

/**
 * We create one State class per declared state on the PHP side, to handle all state specific code here.
 * onEnteringState, onLeavingState and onPlayerActivationChange are predefined names that will be called by the framework.
 * When executing code in this state, you can access the args using this.args
 */
export class MakeBid {
    constructor(private game: Game, private bga: Bga<TrinketTroveTestPlayer, TrinketTroveTestGamedatas>) {
    }

    /**
     * This method is called each time we are entering the game state. You can use this method to perform some user interface changes at this moment.
     */
    onEnteringState(args: MakeBidArgs, isCurrentPlayerActive: boolean) {
        if (isCurrentPlayerActive) {
            this.game.handStock.setSelectionMode("multiple");
            this.game.handStock.onSelectionChange = (selection, lastChange) => {
                this.bga.statusBar.setTitle("${you} must make your bid (" + selection.reduce((acc, cur) => acc + cur.value, 0) + " selected)")
            }
            this.bga.statusBar.addActionButton("Confirm", () => this.confirmBid());
            this.bga.statusBar.addActionButton("Reset", () => this.game.handStock.unselectAll(), {color: "secondary"});
        }
    }

    /**
     * This method is called each time we are leaving the game state. You can use this method to perform some user interface changes at this moment.
     */
    onLeavingState(args: MakeBidArgs, isCurrentPlayerActive: boolean) {
    }

    /**
     * This method is called each time the current player becomes active or inactive in a MULTIPLE_ACTIVE_PLAYER state. You can use this method to perform some user interface changes at this moment.
     * on MULTIPLE_ACTIVE_PLAYER states, you may want to call this function in onEnteringState using `this.onPlayerActivationChange(args, isCurrentPlayerActive)` at the end of onEnteringState.
     * If your state is not a MULTIPLE_ACTIVE_PLAYER one, you can delete this function.
     */
    onPlayerActivationChange(args: MakeBidArgs, isCurrentPlayerActive: boolean) {
        if (!isCurrentPlayerActive) {
            this.game.handStock.setSelectionMode("none");
        }
    }

    confirmBid() {
        this.bga.actions.performAction('actMakeBid', {cards: [this.game.handStock.getSelection().map((card) => card.id)]})
    }
}
