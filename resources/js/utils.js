function currency(value){
    return "₹ "+Number(value).toFixed(2);
}

function scale(value , digits = 2){
    return Number(value).toFixed(digits)
}


export { currency , scale }