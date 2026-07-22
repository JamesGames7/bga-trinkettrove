interface TrinketTroveTestPlayer extends Player {
    energy: number; // any information you add on each result['players']
}

interface TrinketTroveTestGamedatas extends Gamedatas<TrinketTroveTestPlayer> {
    // Add here variables you set up in getAllDatas
    hand: [Card]
}

/*
 * Describe here the types for your state args
 */
interface PlayerTurnArgs {
    
}

interface Card {
    id: number,
    name: String,
    value: number,
    modifiers: [number],
    pos: number
}

/*
 * Describe here the types for your notif args
 */