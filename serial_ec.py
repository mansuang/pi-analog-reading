import serial
serialport = serial.Serial("/dev/ttyAMA0", 9600, timeout=0.5)
serialport.write("status\r\n")
#serialport.write("gpio1 0")
response = serialport.readlines()
print response
