export class GameManager {
    constructor() {
        this.score = 0;
        this.wave = 1;
        this.hp = 5;
        this.ammo = 30;
        this.maxAmmo = 30;
        this.isGameOver = false;
    }

    nextWave() {
        this.wave++;
        this.ammo = this.maxAmmo;
    }

    addScore(pts) {
        this.score += pts;
    }

    takeDamage() {
        this.hp--;
        if (this.hp <= 0) {
            this.isGameOver = true;
        }
    }

    reset() {
        this.score = 0;
        this.wave = 1;
        this.hp = 5;
        this.ammo = 30;
        this.isGameOver = false;
    }
}