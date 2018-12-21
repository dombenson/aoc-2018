package main

import (
	"fmt"
)

const numSteps = 409551
//const numSteps = 2018

type ring struct{
	elfA int
	elfB int
	recipes []int
}

func(this *ring)gen()(newItems []int) {
	sum := this.recipes[this.elfA]+this.recipes[this.elfB]
	if sum < 10 {
		newItems = []int{sum}
	} else {
		newItems = []int{1,sum%10}
	}
	this.recipes = append(this.recipes, newItems...)
	return newItems
}

func(this *ring)step() {
	curCnt := len(this.recipes)
	aSteps := this.recipes[this.elfA] + 1
	this.elfA = (this.elfA+aSteps) % curCnt
	bSteps := this.recipes[this.elfB] + 1
	this.elfB = (this.elfB+bSteps) % curCnt
}

func(this *ring)endswith() int {
	curCnt := len(this.recipes)
	if curCnt < 7 {
		return 0
	}
	if this.recipes[curCnt-1] != 1 {
		if this.recipes[curCnt-2] != 1 {
			return 0
		}
		if this.recipes[curCnt-7] != 4 {
			return 0
		}
		if this.recipes[curCnt-6] != 0 {
			return 0
		}
		if this.recipes[curCnt-5] != 9 {
			return 0
		}
		if this.recipes[curCnt-4] != 5 {
			return 0
		}
		if this.recipes[curCnt-3] != 5 {
			return 0
		}
		return curCnt - 7
	}
	if this.recipes[curCnt-6] != 4 {
		return 0
	}
	if this.recipes[curCnt-5] != 0 {
		return 0
	}
	if this.recipes[curCnt-4] != 9 {
		return 0
	}
	if this.recipes[curCnt-3] != 5 {
		return 0
	}
	if this.recipes[curCnt-2] != 5 {
		return 0
	}

	return curCnt - 6
}


func main() {
	recipes := make([]int, 0, numSteps*2)
	recipes = append(recipes, 3, 7)
	scoreBoard := &ring{0, 1, recipes}
	for {
		scoreBoard.gen()
		e := scoreBoard.endswith()
		if e > 0 {
			fmt.Printf("Appears after: %d\n", e)
			break
		}
		scoreBoard.step()
	}
}