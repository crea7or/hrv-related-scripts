# PHP Scripts to process HRV data files

## Txt file data format

Only one format is supported now, plain text with numbers(one per line) that contain RR intervals:

...
1599
1599
1542
1542
1437
...

This format is used by a few software products including EliteHrv and Kubios.

## What's calculated?

dx - difference between higher and lower RRs
RMSSD - root mean square of RR differences
ln(RMSSD) - natural logarithm from RMSSD
min HR - minimal heart rate detected
max HR - maximal HR detected

## What's inside?

stats.php - the main script that process txt files in the current directory and output to the console. Have a few defines to detect errors.
fix.php - script that fix txt files in current directory (commented out for safety).

