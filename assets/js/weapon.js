import * as THREE from 'three';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';

export class WeaponManager {
    constructor() {
        // 创建独立的 Weapon Scene 防止视角剪裁 (Depth Buffer Overlap)
        this.weaponScene = new THREE.Scene();
        this.weaponCamera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 0.01, 10);
        
        const light = new THREE.DirectionalLight(0xffffff, 1.5);
        light.position.set(1, 2, 1);
        this.weaponScene.add(light);
        this.weaponScene.add(new THREE.AmbientLight(0xffffff, 0.8));

        this.mesh = null;
        this.recoilOffset = 0;
    }

    async load(url) {
        const loader = new GLTFLoader();
        try {
            const gltf = await loader.loadAsync(url);
            this.mesh = gltf.scene;
            
            // 自动缩放调整 FPS 角度
            this.mesh.scale.set(0.1, 0.1, 0.1);
            this.mesh.position.set(0.1, -0.15, -0.3);
            this.mesh.rotation.y = Math.PI;

            this.weaponScene.add(this.mesh);
        } catch (e) {
            // 如果加载失败，创建备用简单枪械模型
            const geom = new THREE.BoxGeometry(0.04, 0.04, 0.2);
            const mat = new THREE.MeshBasicMaterial({ color: 0x333333 });
            this.mesh = new THREE.Mesh(geom, mat);
            this.mesh.position.set(0.1, -0.1, -0.25);
            this.weaponScene.add(this.mesh);
        }
    }

    triggerRecoil() {
        this.recoilOffset = 0.03;
    }

    update(dt) {
        if (this.recoilOffset > 0 && this.mesh) {
            this.mesh.position.z += this.recoilOffset;
            this.recoilOffset -= dt * 0.2;
            if (this.recoilOffset < 0) {
                this.recoilOffset = 0;
                this.mesh.position.set(0.1, -0.15, -0.3);
            }
        }
    }

    render(renderer) {
        renderer.clearDepth();
        renderer.render(this.weaponScene, this.weaponCamera);
    }
}