package main

import (
	"fmt"
	"math"
	"sync"
)

const sn = 3999
const max = 300

func pwr(x,y int) int {
	rackId := x+10
	base := y*rackId
	base += sn
	big := base * rackId
	hundreds := big % 1000
	hun := int(math.Floor(float64(hundreds)/float64(100)))
	return hun-5
}

func main() {
	grid := make([][]int, max)
	for i,_ := range grid {
		grid[i] = make([]int, max)
		for j,_ := range grid[i] {
			grid[i][j] = pwr(i,j)
		}
	}

	var pwrLock sync.Mutex
	var bestPower, bestX, bestY, bestSz int

	var wg sync.WaitGroup

	for usesz := 1; usesz<= max; usesz++ {
		wg.Add(1)
		go func(sz int) {
			defer wg.Done()
			thisMax := max - sz
			for i := 0; i < thisMax; i++ {
				for j := 0; j < thisMax; j++ {
					thisPwr := 0
					for ox := 0; ox < sz; ox++ {
						for oy := 0; oy < sz; oy++ {
							thisPwr += grid[i+ox][j+oy]
						}
					}
					pwrLock.Lock()
					if thisPwr > bestPower {
						bestPower = thisPwr
						bestX = i
						bestY = j
						bestSz = sz
					}
					pwrLock.Unlock()
				}
			}
		}(usesz)
	}
	wg.Wait()
	fmt.Printf("Triplet: %d,%d,%d (pwr %d)\n", bestX, bestY, bestSz, bestPower)
}