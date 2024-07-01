import fs from 'fs'

const isProduction = process.env.NODE_ENV === 'production'

function removeHotFile() {
  if (fs.existsSync('hot')) fs.rmSync('hot')
}

export default function hotFilePlugin() {
  if (!isProduction) {
    fs.writeFileSync('hot', 'hot')
  }

  process.on('exit', removeHotFile)
  process.on('SIGINT', () => process.exit())
  process.on('SIGTERM', () => process.exit())
  process.on('SIGHUP', () => process.exit())

  return {
      name: 'hot-file-plugin',
  }
}
