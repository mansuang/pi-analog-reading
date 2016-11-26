import spidev, time
import MySQLdb
import ConfigParser

config = ConfigParser.ConfigParser()
config.readfp(open('/home/pi/pi-analog-reading/config.ini'))

channel = 0

db = MySQLdb.connect(host=config.get('MYSQL','host'),       # your host, usually localhost
                     user=config.get('MYSQL','username'),   # your username
                     passwd=config.get('MYSQL','password'),  # your password
                     db=config.get('MYSQL','db'))        # name of the data base

cur = db.cursor()

def log_data(channel,reading, voltage, temp):
    try:
       cur.execute("INSERT INTO `temp` (`id`, `reading`, `voltage`, `temp`, `created_at`) VALUES (%d, '%d', '%2.2f', '%2.2f', NOW()) ON DUPLICATE KEY UPDATE reading='%d', voltage='%2.2f', temp='%2.2f', created_at=NOW()" % (channel+1, reading, voltage,temp,reading, voltage,temp))
       db.commit()
    except:
       db.rollback()



spi = spidev.SpiDev()

spi.open(0,0)



def analog_read(channel):

    r = spi.xfer2([4 | 2 |(channel>>2), (channel &3) << 6,0])

    adc_out = ((r[1]&15) << 8) + r[2]

    return adc_out


while True:

    reading = analog_read(channel)

    voltage = reading * 3.3 / 4096

    Temp = voltage * 99.5

    log_data(channel, reading, voltage, Temp)

    print("Reading=%d\tVoltage=%f\tTemp=%2.2f" % (reading, voltage,Temp))

    time.sleep(1)
