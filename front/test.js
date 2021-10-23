import blable from 'test.js';

class module {
    constructor(img) {
        this.obj = img.style;
    }

    setPosition(property, number) {
        return property + number + "px";
    }

    simulate(value, number) {
        return value + number > 0 && value + number < 2000 ? true : false;
    }

    move(direction) {

        var previousY = parseInt(this.obj.top.replace(/px/,""));
        var previousX = parseInt(this.obj.left.replace(/px/,""));
        var movement = null;

        switch(direction) {
            case 'left':
                movement = -50
                if(this.simulate(previousX, movement)) {
                    break
                }
                this.obj.left = this.setPosition(previousX, movement);
                break;
            case 'right':
                this.obj.right = this.setPosition(previousX, + 50);
                break;
            case 'top':
                this.obj.top  = this.setPosition(previousY, - 50);
                break;
            case 'bottom':
                this.obj.botom = this.setPosition(previousY, 50);
                break;
            default:
                break;
        }
    }
}

export default module;