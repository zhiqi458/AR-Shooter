import * as THREE from 'three';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';

export class Enemy {
    constructor(targetAnchor) {
        this.anchor = targetAnchor;
        this.mesh = null;
        this.mixer = null;
        this.actions = {};
        this.hp = 5;
        this.speed = 0.25;
        this.isAlive = true;
    }

    async load(url) {
        const loader = new GLTFLoader();
        const gltf = await loader.loadAsync(url);
        this.mesh = gltf.scene;

        // 自动缩放适应目标图比例
        const bbox = new THREE.Box3().setFromObject(this.mesh);
        const size = bbox.getSize(new THREE.Vector3());
        const maxDim = Math.max(size.x, size.y, size.z);
        const scale = 0.5 / (maxDim || 1);
        this.mesh.scale.set(scale, scale, scale);

        // 设置初始位置
        this.mesh.position.set(0, 0, 0);
        this.anchor.add(this.mesh);

        // 自动检索播放动画
        if (gltf.animations && gltf.animations.length > 0) {
            this.mixer = new THREE.AnimationMixer(this.mesh);
            gltf.animations.forEach((clip) => {
                this.actions[clip.name.toLowerCase()] = this.mixer.clipAction(clip);
            });

            // 智能回退匹配行走动画
            const walkAction = this.actions['walk'] || this.actions['run'] || Object.values(this.actions)[0];
            if (walkAction) walkAction.play();
        }
    }

    update(dt, playerLocalPos) {
        if (!this.isAlive || !this.mesh) return;

        if (this.mixer) this.mixer.update(dt);

        // 朝玩家移动
        const dir = new THREE.Vector3().subVectors(playerLocalPos, this.mesh.position).normalize();
        this.mesh.position.addScaledVector(dir, this.speed * dt);
        this.mesh.lookAt(playerLocalPos);
    }

    takeDamage(amount) {
        this.hp -= amount;
        if (this.hp <= 0) {
            this.isAlive = false;
            this.anchor.remove(this.mesh);
        }
    }
}