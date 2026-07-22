/**
 * We create one State class per declared state on the PHP side, to handle all state specific code here.
 * onEnteringState, onLeavingState and onPlayerActivationChange are predefined names that will be called by the framework.
 * When executing code in this state, you can access the args using this.args
 */
class PlayerTurn {
    constructor(game, bga) {
        this.game = game;
        this.bga = bga;
    }
    /**
     * This method is called each time we are entering the game state. You can use this method to perform some user interface changes at this moment.
     */
    onEnteringState(args, isCurrentPlayerActive) {
    }
    /**
     * This method is called each time we are leaving the game state. You can use this method to perform some user interface changes at this moment.
     */
    onLeavingState(args, isCurrentPlayerActive) {
    }
    /**
     * This method is called each time the current player becomes active or inactive in a MULTIPLE_ACTIVE_PLAYER state. You can use this method to perform some user interface changes at this moment.
     * on MULTIPLE_ACTIVE_PLAYER states, you may want to call this function in onEnteringState using `this.onPlayerActivationChange(args, isCurrentPlayerActive)` at the end of onEnteringState.
     * If your state is not a MULTIPLE_ACTIVE_PLAYER one, you can delete this function.
     */
    onPlayerActivationChange(args, isCurrentPlayerActive) {
    }
    onCardClick(card_id) {
        console.log('onCardClick', card_id);
        this.bga.actions.performAction("actPlayCard", {
            card_id,
        }).then(() => {
            // What to do after the server call if it succeeded
            // (most of the time, nothing, as the game will react to notifs / change of state instead, so you can delete the `then`)
        });
    }
}

/*
To use the BGA libs, add `import { BgaAnimations, BgaCards } from "./libs";` in the files using them.

To get the latest typing files (that you would save at the root of your game), read the lib doc and see the demo, go to:
https://en.doc.boardgamearena.com/BgaAnimations
https://en.doc.boardgamearena.com/BgaCards
*/
const BgaAnimations = await globalThis.importEsmLib('bga-animations', '1.x');
const BgaCards = await globalThis.importEsmLib('bga-cards', '1.x');

class Game {
    constructor(bga) {
        this.marketStock = [];
        this.colorOrder = ["85568d", "8f2f27", "379d9b", "dc7b70", "db812e", "nimbus", "198a43", "2c5b8d", "efae49", "4d5b2b", "3c2a56", "795133", "404e6c", "2a2a2a"];
        console.log('trinkettrovetest constructor');
        this.bga = bga;
        // Declare the State classes
        this.playerTurn = new PlayerTurn(this, bga);
        this.bga.states.register('PlayerTurn', this.playerTurn);
        // Uncomment the next line to show debug informations about state changes in the console. Remove before going to production!
        // this.bga.states.logger = console.log;
        // Here, you can init the global variables of your user interface
        // Example:
        // this.myGlobalValue = 0;
    }
    /*
        setup:
        
        This method must set up the game user interface according to current game situation specified
        in parameters.
        
        The method is called each time the game interface is displayed to a player, ie:
        _ when the game starts
        _ when a player refreshes the game page (F5)
        
        "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
    */
    setup(gamedatas) {
        console.log("Starting game setup");
        this.gamedatas = gamedatas;
        this.animationManager = new BgaAnimations.Manager({
            animationsActive: () => this.bga.gameui.bgaAnimationsActive(),
        });
        this.cardsManager = new BgaCards.Manager({
            animationManager: this.animationManager,
            type: "trinket",
            getId: (card) => card.id,
            cardWidth: 128,
            cardHeight: 178,
            cardBorderRadius: '5px',
            isCardVisible: () => true,
            setupFrontDiv(card, element) {
                element.style.backgroundPositionX = `-${card.pos % 10}00%`;
                element.style.backgroundPositionY = `-${Math.floor(card.pos / 10)}00%`;
            },
            setupBackDiv(card, element) {
                element.style.backgroundPosition = `-900% -200%`;
            }
        });
        // Example to add a div on the game area
        this.bga.gameArea.getElement().insertAdjacentHTML('beforeend', `
            <div id="player-tables"></div>
        `);
        // TODO: Set up your game interface here, according to "gamedatas"
        $('game_play_area').insertAdjacentHTML("beforeend", `
            <div id="marketStock" class="whiteblock"><div id="marketHorizontal"></div></div>
            <div id="bidStock" class="whiteblock"></div>
            <div id="playerOrder" class="whiteblock"></div>
            <div id="handStock"></div>
        `);
        let i = 0;
        gamedatas.market.forEach(slot => {
            $('marketHorizontal').insertAdjacentHTML(`beforeend`, `
                <div id="marketSlot-${i}"></div>
            `);
            this.marketStock.push(new BgaCards.LineStock(this.cardsManager, $('marketSlot-' + i), {
                direction: "column",
            }));
            let j = 0;
            slot.forEach(card => {
                this.marketStock[i].addCard(card);
                this.marketStock[i].getCardElement(card).classList.add("item-" + j);
                j++;
            });
            i++;
        });
        // TODO change
        gamedatas.playerorder.forEach(id => {
            let info = gamedatas.players[id];
            let index = this.colorOrder.indexOf(info.color);
            console.log(index);
            $('playerOrder').insertAdjacentHTML(`beforeend`, `
                <div id="playerTile-${info.id}" class="playerTile" style="background-position-x: ${`-${index}00%`}"></div>
            `);
        });
        $("playerOrder").insertAdjacentHTML("afterbegin", `
            <div id="first" class="firstLast"></div>
        `);
        $("playerOrder").insertAdjacentHTML("beforeend", `
            <div id="last" class="firstLast"></div>
        `);
        this.handStock = new BgaCards.HandStock(this.cardsManager, $('handStock'), {
            sort(a, b) {
                if (a.value - b.value != 0) {
                    return a.value - b.value;
                }
                return a.name < b.name ? -1 : 1;
            },
        });
        this.handStock.addCards(gamedatas.hand);
        // Setup game notifications to handle (see "setupNotifications" method below)
        this.setupNotifications();
        console.log("Ending game setup");
    }
    ///////////////////////////////////////////////////
    //// Utility methods
    /*
    
        Here, you can defines some utility methods that you can use everywhere in your javascript
        script. Typically, functions that are used in multiple state classes or outside a state class.
    
    */
    ///////////////////////////////////////////////////
    //// Reaction to cometD notifications
    /*
        setupNotifications:
        
        In this method, you associate each of your game notifications with your local method to handle it.
        
        Note: game notification names correspond to "bga->notify->all" calls in your Game.php file.
    
    */
    setupNotifications() {
        console.log('notifications subscriptions setup');
        // automatically listen to the notifications, based on the `notif_xxx` function on this class. 
        // Uncomment the logger param to see debug information in the console about notifications.
        this.bga.notifications.setupPromiseNotifications({
        // logger: console.log
        });
    }
    // TODO: from this point and below, you can write your game notifications handling methods
    async notif_marketAdded(args) {
        console.log(args);
    }
}

export { Game };
