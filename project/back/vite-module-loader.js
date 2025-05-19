// back/vite-modules-loader.js
import fs from 'fs/promises';
import path from 'path';
import { pathToFileURL } from 'url';
async function collectModuleAssetsPaths(paths, modulesPath) {
  modulesPath = path.join(__dirname, modulesPath);
  const moduleStatusesPath = path.join(__dirname, 'modules_statuses.json');
  try {
    const moduleStatusesContent = await fs.readFile(moduleStatusesPath, 'utf-8');
    const moduleStatuses = JSON.parse(moduleStatusesContent);
    const moduleDirectories = await fs.readdir(modulesPath);
    for (const moduleDir of moduleDirectories) {
      if (moduleDir === '.DS_Store') {
        continue;
      }
      if (moduleStatuses[moduleDir] === true) {
        const viteConfigPath = path.join(modulesPath, moduleDir, 'vite.config.js');
        try {
          await fs.access(viteConfigPath);
          const moduleConfigURL = pathToFileURL(viteConfigPath);
          const moduleConfig = await import(moduleConfigURL.href);
          if (moduleConfig.paths && Array.isArray(moduleConfig.paths)) {
            paths.push(...moduleConfig.paths);
          }
        } catch (error) {
          // vite.config.js does not exist, skip this module
        }
      }
    }
  } catch (error) {
    console.error(`Error reading module statuses or module configurations: ${error}`);
  }
  return paths;
}
export default collectModuleAssetsPaths;
