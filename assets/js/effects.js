import * as THREE from 'three';

export class EffectsManager {
    constructor(scene) {
        this.scene = scene;
        this.particles = [];
    }

    // 击中火花特效
    createHitEffect(position) {
        const pCount = 15;
        const geom = new THREE.BufferGeometry();
        const positions = [];

        for (let i = 0; i < pCount; i++) {
            positions.push(position.x, position.y, position.z);
        }

        geom.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));
        const mat = new THREE.PointsMaterial({ color: 0xffb703, size: 0.05, transparent: true, opacity: 1 });
        const pSystem = new THREE.Points(geom, mat);

        pSystem.userData = {
            velocities: Array.from({ length: pCount }, () => new THREE.Vector3(
                (Math.random() - 0.5) * 0.2,
                (Math.random() - 0.5) * 0.2,
                (Math.random() - 0.5) * 0.2
            )),
            life: 1.0
        };

        this.scene.add(pSystem);
        this.particles.push(pSystem);
    }

    update(dt) {
        for (let i = this.particles.length - 1; i >= 0; i--) {
            const p = this.particles[i];
            p.userData.life -= dt * 3.0;

            if (p.userData.life <= 0) {
                this.scene.remove(p);
                p.geometry.dispose();
                p.material.dispose();
                this.particles.splice(i, 1);
            } else {
                p.material.opacity = p.userData.life;
                const pos = p.geometry.attributes.position.array;
                for (let j = 0; j < p.userData.velocities.length; j++) {
                    pos[j * 3] += p.userData.velocities[j].x;
                    pos[j * 3 + 1] += p.userData.velocities[j].y;
                    pos[j * 3 + 2] += p.userData.velocities[j].z;
                }
                p.geometry.attributes.position.needsUpdate = true;
            }
        }
    }
}