import { reactive } from 'vue'

export enum DeviceSize { sm, md, lg, xl, "2xl" }

export type DeviceInfo = {
    windowWidth: number,
    size: DeviceSize,
}

const calcSize = (width: number): DeviceSize => {
    if (width < 640) return DeviceSize.sm;
    if (width < 768) return DeviceSize.md;
    if (width < 1024) return DeviceSize.lg;
    if (width < 1280) return DeviceSize.xl;
    return DeviceSize[`2xl`]
};

const deviceInfo = reactive({
    windowWidth: window.innerWidth,
    size: DeviceSize.sm,
});

deviceInfo.size = calcSize(window.innerWidth);

window.addEventListener('resize', () => {
    const width = window.innerWidth;
    deviceInfo.windowWidth = width;
    deviceInfo.size = calcSize(width)
    console.log(deviceInfo.size)
});

export const useDevice = (): DeviceInfo => {
    return deviceInfo
};

export default useDevice